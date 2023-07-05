<?php

namespace App\Http\Controllers;

use App\Jobs\SendSms;
use App\Models\Event;
use App\Models\Order;
use PayPal\Api\Payer;
use App\Models\Budget;
use PayPal\Api\Amount;
use PayPal\Api\Payment;
use App\Helpers\Pesapal;
use App\Models\Category;
use PayPal\Api\WebProfile;
use App\Models\CardPayment;
use Illuminate\Support\Str;
use App\Models\MpesaPayment;
use Illuminate\Http\Request;
use App\Models\PaypalPayment;
use App\Models\Payment as Pay;
use App\Models\PesapalPayment;
use PayPal\Api\PaymentExecution;
use App\Models\BudgetTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use PayPal\Auth\OAuthTokenCredential;
use App\Models\PesapalNotificationUrl;
use Illuminate\Support\Facades\Config;
use App\Notifications\ClientNotification;
use App\Notifications\VendorNotification;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\MpesaPaymentController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class CheckoutController extends Controller
{
   public function orderCheckout(Request $request)
   {
      if (collect($request->orders)->isEmpty()) {
         return back()->with('error', 'Please select an order before checkout');
      }
      $user = auth()->user();
      $price = 0;
      $orders = [];
      foreach($request->orders as $key => $value){
         $order = Order::find($key);
         $price += $order->service_pricing ? (int) $order->service_pricing->service_pricing_price : (int) $order->order_quotation->order_pricing_price;
         array_push($orders, $order);
      }
      return view('client.checkout', compact('orders', 'price', 'user'));
   }

   public function ordersCheckout(Request $request)
   {
      if (collect($request->orders)->isEmpty()) {
         return back()->with('error', 'Please select an order before checkout');
      }
      $user = auth()->user();
      $price = 0;
      $orders = [];
      foreach($request->orders as $key => $value){
         $order = Order::find($key);
         $price += $order->service_pricing ? $order->service_pricing->service_pricing_price : $order->order_quotation->order_pricing_price;
         array_push($orders, $order);
      }
      return view('client.checkout', compact('orders', 'price', 'user'));
   }

   public function iveriSuccessCallback(Request $request)
   {
      foreach($request['ORDERS'] as $key => $value) {
         $updateOrder = Order::find($value);

         // Update the orders to reflect payment
         $updateOrder->update([
            'status' => 'Paid',
         ]);

         // If the Order has a linked event, add transaction to the budgets table
         if ($updateOrder->event_id != null) {
            $event = Event::find($updateOrder->event_id);
            if ($event) {
               $budget = Budget::firstOrCreate([
                  'event_id' => $event->id,
                  'title' => 'Initial Budget',
               ]);

               $category = Category::find($updateOrder->service->category_id);

               BudgetTransaction::create([
                  'budget_id' => $budget->id,
                  'event_id' => $updateOrder->event_id,
                  'type' => 'Expense',
                  'title' => 'Order '. $updateOrder->order_id.' payment',
                  'description' => 'An expense for payment of the order '.$updateOrder->order_id,
                  'amount' => $updateOrder->service_pricing ? $updateOrder->service_pricing->service_pricing_price : $updateOrder->order_quotation->order_pricing_price,
                  'date' => now(),
                  'reference' => $request['MERCHANTREFERENCE'],
                  'transaction_service_category' => $category->name,
               ]);
            }
         }
         // Update payments table to show the payment made
         Pay::create([
            'order_id' => $updateOrder->id,
            'user_id' => $updateOrder->user_id,
            'amount' => $updateOrder->service_pricing ? $updateOrder->service_pricing->service_pricing_price : $updateOrder->order_quotation->order_pricing_price,
            'payment_method' => $request['PAYMENT_METHOD'],
            'transaction_id' => $request['MERCHANTREFERENCE']
         ]);

         // Update card payments table
         CardPayment::create([
            'order_id' => $updateOrder->id,
            'checkout_id' => $request['MERCHANTREFERENCE']
         ]);

         // Notify Vendor
         $updateOrder->service->vendor->notify(new VendorNotification($updateOrder, 'Order Paid'));
         SendSms::dispatchAfterResponse($updateOrder->service->vendor->company_phone_number, 'The client completed the payment for the order '.$updateOrder->order_id);
         // Notify Client
         $updateOrder->user->notify(new ClientNotification($updateOrder, 'Successful Payment'));
      }

      return redirect()->route('client.orders')->with('success', 'Payment Successful');
   }

   public function iveriFailedCallback(Request $request)
   {
      return redirect()->route('client.orders')->with('error', 'Payment NOT Successful');
   }

   public function orderPayment(Request $request)
   {
      $account_reference = Str::upper(Str::random(3)).time().Str::upper(Str::random(3));
      if (strlen($request->phone_number) == 9) {
         $phone_number = '254'.$request->phone_number;
      } else {
         $phone_number = '254'.substr($request->phone_number, -9);
      }

      switch ($request->payment_method) {
         case 'Pesapal':
            $token = Pesapal::accessToken();
            $url = Pesapal::baseUrl().'/api/Transactions/SubmitOrderRequest';
            $notification_id = PesapalNotificationUrl::where('payment_purpose', 'Order Payment')->first()->ipn_id;
            $response = Http::timeout(3)
               ->withToken($token)->withHeaders([
                  'Content-Type' => 'application/json',
                  'Accept' => 'application/json',
               ])
               ->post($url, [
                  'id' => $account_reference,
                  'currency' => 'KES',
                  'amount' => $request->total_price,
                  'description' => 'Payment of Orders',
                  'callback_url' => route('pesapal.order.payment.success.callback'),
                  'cancellation_url' => route('pesapal.order.payment.failed.callback'),
                  'notification_id' => $notification_id,
                  'billing_address' => [
                     'phone_number' => auth()->user()->phone_number,
                     'email_address' => auth()->user()->email,
                     'country_code' => 'KE',
                     'first_name' => auth()->user()->first_name,
                     'last_name' => auth()->user()->last_name,
                  ]
               ]);

            foreach ($request->orders as $key => $order) {
               PesapalPayment::create([
                  'payable_id' => $order,
                  'payable_type' => Order::class,
                  'tracking_id' => json_decode($response)->order_tracking_id,
               ]);
            }

            $redirect_url = json_decode($response)->redirect_url;
            return redirect($redirect_url);
         case 'Mpesa':
            $mpesa = new MpesaPaymentController();
            $results = $mpesa->stkPush(
               $phone_number,
               $request->total_price,
               route('stk.push.callback'),
               $account_reference,
               'Payment of Orders'
            );

            if ($results['response_code'] != null) {
               foreach ($request->orders as $key => $order) {
                  MpesaPayment::create([
                     'order_id' => $order,
                     'account' => $account_reference,
                     'checkout_request_id' => $results['checkout_request_id'],
                     'phone' => $phone_number
                  ]);
               }

               return redirect()->route('client.orders')->with('success', 'Please enter your MPESA Pin on your phone to complete payment.');
            } else {
               return redirect()->route('client.orders')->with('error', 'An error occured. Please try again.');
            }
            break;
         case 'Paypal':
            $checkout_id = Str::upper(Str::random(8));

            foreach ($request->orders as $key => $order) {
               $paypalPayment = new PaypalPayment();
               $paypalPayment->order_id = $order;
               $paypalPayment->checkout_id = $checkout_id;
               $paypalPayment->save();
            }

            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $amount = new Amount();
            $amount->setCurrency('USD')
               ->setTotal($request->total_price);

            $transaction = new Transaction();
            $transaction->setAmount($amount)
               ->setDescription('Payment of Order '.$checkout_id);

            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(URL::route('order.payment.status', $checkout_id))
               ->setCancelUrl(URL::route('client.orders'));

            $inputFields = new InputFields();
            $inputFields->setNoShipping(1);

            $webProfile = new WebProfile();
            $webProfile->setName('test' . uniqid())->setInputFields($inputFields);

            $webProfileId = $webProfile->create($this->_api_context)->getId();

            $payment = new Payment();
            $payment->setExperienceProfileId($webProfileId);
            $payment->setIntent('Sale')
               ->setPayer($payer)
               ->setRedirectUrls($redirect_urls)
               ->setTransactions(array($transaction));
            try {
               $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
               if (Config::get('app.debug')) {
                     Session::put('error','Connection timeout');
                     return Redirect::route('client.orders');
               } else {
                     Session::put('error','Some error occur, sorry for inconvenient');
                     return Redirect::route('client.orders');
               }
            }

            foreach($payment->getLinks() as $link) {
               if($link->getRel() == 'approval_url') {
                     $redirect_url = $link->getHref();
                     break;
               }
            }

            Session::put('paypal_payment_id', $payment->getId());

            if(isset($redirect_url)) {
               return Redirect::away($redirect_url);
            }

            Session::put('error','Unknown error occurred');
            return Redirect::route('client.orders');

            break;
         default:
            return redirect()->route('client.orders')->with('error', 'Please select a payment method');
            break;
      }
   }
}
