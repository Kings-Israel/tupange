<?php

namespace App\Http\Controllers;

use App\Jobs\SendSms;
use App\Models\Event;
use App\Models\Order;
use App\Models\Budget;
use App\Models\Payment;
use App\Helpers\Pesapal;
use App\Models\Category;
use App\Models\EventProgram;
use Illuminate\Http\Request;
use App\Models\PesapalPayment;
use App\Models\EventGuestDetail;
use App\Models\BudgetTransaction;
use App\Models\EventTicketPayment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Notifications\ClientNotification;
use App\Notifications\VendorNotification;
use App\Notifications\EventProgramNotification;

class PesapalPaymentController extends Controller
{
   public function getRegisteredIpns()
   {
      return Pesapal::getRegisteredIpns();
   }

   public function registerOrderPaymentIpn()
   {
      Pesapal::registerOrderPaymentIpnUrls();
   }

   public function registerTicketPaymentIpn()
   {
      Pesapal::registerTicketPaymentIpnUrls();
   }

   public function registerEventProgramPaymentIpn()
   {
      Pesapal::registerProgramPaymentIpnUrls();
   }

   public function orderPaymentNotification(Request $request)
   {
      info($request->all());
   }

   public function programPaymentNotification(Request $request)
   {
      info($request->all());
   }

   public function ticketPaymentNotification(Request $request)
   {
      info($request->all());
   }

   public function orderPaymentSuccessCallback(Request $request)
   {
      $order_tracking_id = $request->query('OrderTrackingId');
      $token = Pesapal::accessToken();
      $url = Pesapal::baseUrl().'/api/Transactions/GetTransactionStatus?orderTrackingId='.$order_tracking_id;

      $response = Http::withToken($token)
                        ->acceptJson()
                        ->withHeaders([
                           'Content-Type' => 'application/json'
                        ])
                        ->get($url);

      $response = json_decode($response);
      if ($response->status_code == 1) {
         $pesapal_payments = PesapalPayment::where('tracking_id', $order_tracking_id)->get();

         foreach ($pesapal_payments as $key => $pesapal_payment) {
            $order = Order::find($pesapal_payment->payable_id);
            $order->status = "Paid";
            $order->save();

            $pesapal_payment->amount = $response->amount;
            $pesapal_payment->account_number = $response->payment_account;
            $pesapal_payment->transaction_id = $response->confirmation_code;
            $pesapal_payment->save();

            // If the Order has a linked event, add transaction to the budgets table
            if ($order->event_id != null) {
               $event = Event::find($order->event_id);
               if ($event) {
                  $budget = Budget::firstOrCreate([
                     'event_id' => $event->id,
                     'title' => 'Initial Budget',
                  ]);

                  $category = Category::find($order->service->category_id);

                  BudgetTransaction::create([
                     'budget_id' => $budget->id,
                     'event_id' => $order->event_id,
                     'type' => 'Expense',
                     'title' => 'Order '. $order->order_id.' payment',
                     'description' => 'An expense for payment of the order '.$order->order_id,
                     'amount' => $order->service_pricing ? $order->service_pricing->service_pricing_price : $order->order_quotation->order_pricing_price,
                     'date' => now(),
                     'reference' => $response->confirmation_code,
                     'transaction_service_category' => $category->name,
                  ]);
               }
            }

            Payment::create([
               'order_id' => $order->id,
               'user_id' => $order->user_id,
               'amount' => $order->service_pricing ? $order->service_pricing->service_pricing_price : $order->order_quotation->order_pricing_price,
               'payment_method' => 'Pesapal',
               'transaction_id' => $response->confirmation_code
            ]);

            // Notify Vendor
            $order->service->vendor->notify(new VendorNotification($order, 'Order Paid'));
            // Notify Client
            $order->user->notify(new ClientNotification($order, 'Successful Payment'));
            SendSms::dispatchAfterResponse($order->service->vendor->company_phone_number, 'The client completed the payment for the order '.$order->order_id);
         }

         session()->put('success', 'Payment was successful');
      } else {
         session()->put('error', 'Payment was not successful');
      }
      return redirect()->route('client.orders');
   }

   public function orderPaymentFailedCallback(Request $request)
   {
      session()->put('error', 'Payment was not successful');

      return redirect()->route('client.orders');
   }

   public function programPaymentSuccessCallback(Request $request)
   {
      $order_tracking_id = $request->query('OrderTrackingId');
      $token = Pesapal::accessToken();
      $url = Pesapal::baseUrl().'/api/Transactions/GetTransactionStatus?orderTrackingId='.$order_tracking_id;

      $response = Http::withToken($token)
                        ->acceptJson()
                        ->withHeaders([
                           'Content-Type' => 'application/json'
                        ])
                        ->get($url);

      $response = json_decode($response);
      if ($response->status_code == 1) {
         $pesapal_payment = PesapalPayment::where('tracking_id', $order_tracking_id)->first();
         $pesapal_payment->amount = $response->amount;
         $pesapal_payment->account_number = $response->payment_account;
         $pesapal_payment->transaction_id = $response->confirmation_code;
         $pesapal_payment->save();

         $eventProgram = EventProgram::where('id', $pesapal_payment->payable_id)->first();

         $eventProgram->update([
            'payment_id' => $response->confirmation_code,
            'canDownload' => true
         ]);

         $eventProgram->user->notify(new EventProgramNotification($eventProgram, 'success'));
         SendSms::dispatch($eventProgram->user->phone_number, 'The event program, '. $eventProgram->event_name.', can now be downloaded and shared');
      }

      return redirect()->route('client.program.show', $eventProgram)->with('success', 'Payment Successful. You can download the program.');
   }

   public function programPaymentFailedCallback(Request $request)
   {
      session()->put('error', 'Payment was not successful');

      return redirect()->route('client.programs.index');
   }

   public function ticketPaymentSuccessCallback(Request $request)
   {
      $order_tracking_id = $request->query('OrderTrackingId');
      $token = Pesapal::accessToken();
      $url = Pesapal::baseUrl().'/api/Transactions/GetTransactionStatus?orderTrackingId='.$order_tracking_id;

      $response = Http::withToken($token)
                        ->acceptJson()
                        ->withHeaders([
                           'Content-Type' => 'application/json'
                        ])
                        ->get($url);

      $response = json_decode($response);
      if ($response->status_code == 1) {
         $pesapal_payment = PesapalPayment::where('tracking_id', $order_tracking_id)->first();
         $pesapal_payment->amount = $response->amount;
         $pesapal_payment->account_number = $response->payment_account;
         $pesapal_payment->transaction_id = $response->confirmation_code;
         $pesapal_payment->save();

         $guest = EventGuestDetail::find($pesapal_payment->payable_id);

         $guest->update([
            'is_paid' => true,
         ]);

         $ticket_payment = EventTicketPayment::where('event_guest_detail_id', $guest->id)->first();

         $ticket_payment->update([
            'payment_method' => $response->payment_method,
            'transaction_id' => $response->confirmation_code,
         ]);
      }

      return redirect()->route('event.ticket.show', $guest->barcode)->with('success', 'Payment Successful.');
   }

   public function ticketPaymentFailedCallback(Request $request)
   {
      return redirect('/');
   }
}
