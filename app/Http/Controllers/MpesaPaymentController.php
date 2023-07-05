<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Jobs\SendSms;
use App\Models\Event;
use App\Models\Order;
use App\Helpers\Mpesa;
use App\Models\Budget;
use App\Models\Payment;
use PayPal\Api\Invoice;
use App\Models\Category;
use Barryvdh\DomPDF\PDF;
use App\Models\MpesaPayment;
use Illuminate\Http\Request;
use App\Models\BudgetTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Notifications\ClientNotification;
use App\Notifications\VendorNotification;

class MpesaPaymentController extends Controller
{
   /**
    * @return bool|string
    */
   public function b2c($phone, $amount, $callback, $account_number, $remarks)
   {
      $amount = 5;
      $phone = 254708374149;
      $remarks = 'Sell bonds';
      $callback = route('mpesa.b2c.callback');
      $url = Mpesa::oxerus_mpesaGetB2CUrl();
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . Mpesa::oxerus_mpesaGenerateAccessToken()));
      $curl_post_data = [
         'InitiatorName' => config('services.mpesa.initiator_name'),
         'CommandID' => 'BusinessPayment',
         'SecurityCredential' => Mpesa::oxerus_mpesaSecurityCredentials(),
         'QueueTimeOutURL' => $callback,
         'ResultURL' => $callback,
         'Amount' => $amount,
         'PartyA' => config('services.mpesa.business_shortcode'),
         'PartyB' => $phone,
         'Remarks' => $remarks,
         'Occasion' => $remarks,
         'TransactionDesc' => $remarks,
      ];
      $data_string = json_encode($curl_post_data);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
      $curl_response = curl_exec($curl);
      return $curl_response;
   }

   /**
    * @param $phone
    * @param $amount
    * @param $callback
    * @param $account_number
    * @param $remarks
    * @return array
    */
   public function stkPush($phone, $amount, $callback, $account_number, $remarks)
   {
      $url = Mpesa::oxerus_mpesaGetStkPushUrl();
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . Mpesa::oxerus_mpesaGenerateAccessToken()));
      $curl_post_data = [
         'BusinessShortCode' => config('services.mpesa.business_shortcode'),
         'Password' => Mpesa::oxerus_mpesaLipaNaMpesaPassword(),
         'Timestamp' => Carbon::now()->format('YmdHis'),
         'TransactionType' => 'CustomerPayBillOnline',
         'Amount' => $amount,
         'PartyA' => $phone,
         'PartyB' => config('services.mpesa.business_shortcode'),
         'PhoneNumber' => $phone,
         'CallBackURL' => $callback,
         'AccountReference' => $account_number,
         'TransactionDesc' => $remarks,
      ];
      $data_string = json_encode($curl_post_data);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($curl, CURLOPT_POST, true);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
      $curl_response = curl_exec($curl);
      $responseObj = json_decode($curl_response);
      $response_details = [
         "merchant_request_id" => $responseObj->MerchantRequestID ?? null,
         "checkout_request_id" => $responseObj->CheckoutRequestID ?? null,
         "response_code" => $responseObj->ResponseCode ?? null,
         "response_desc" => $responseObj->ResponseDescription ?? null,
         "customer_msg" => $responseObj->CustomerMessage ?? null,
         "phone" => $phone,
         "amount" => $amount,
         "remarks" => $remarks
      ];

      info($curl_response);

      return $response_details;
   }

   /**
    * @param Request $request
    */
   public function stkPushCallback(Request $request)
   {
      $callbackJSONData = file_get_contents('php://input');
      $callbackData = json_decode($callbackJSONData);

      info($callbackJSONData);

      $result_code = $callbackData->Body->stkCallback->ResultCode;
      // $result_desc = $callbackData->Body->stkCallback->ResultDesc;
      $merchant_request_id = $callbackData->Body->stkCallback->MerchantRequestID;
      $checkout_request_id = $callbackData->Body->stkCallback->CheckoutRequestID;
      $amount = $callbackData->Body->stkCallback->CallbackMetadata->Item[0]->Value;
      $mpesa_receipt_number = $callbackData->Body->stkCallback->CallbackMetadata->Item[1]->Value;
      // $transaction_date = $callbackData->Body->stkCallback->CallbackMetadata->Item[3]->Value;
      // $phone_number = $callbackData->Body->stkCallback->CallbackMetadata->Item[4]->Value;


      $result = [
         // "result_desc" => $result_desc,
         "result_code" => $result_code,
         "merchant_request_id" => $merchant_request_id,
         "checkout_request_id" => $checkout_request_id,
         "amount" => $amount,
         "mpesa_receipt_number" => $mpesa_receipt_number,
         // "phone" => $phone_number,
         // "transaction_date" => Carbon::parse($transaction_date)->toDateTimeString()
      ];

      if($result['result_code'] == 0) {
         $mpesaPayments = MpesaPayment::where('checkout_request_id', $result['checkout_request_id'])->get();

         foreach ($mpesaPayments as $payment) {
            $order = Order::find($payment->order_id);
            $order->status = "Paid";
            $order->save();

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
                     'reference' => $result['mpesa_receipt_number'],
                     'transaction_service_category' => $category->name,
                  ]);
               }
            }

            Payment::create([
               'order_id' => $order->id,
               'user_id' => $order->user_id,
               'amount' => $order->service_pricing ? $order->service_pricing->service_pricing_price : $order->order_quotation->order_pricing_price,
               'payment_method' => 'mpesa',
               'transaction_id' => $result['mpesa_receipt_number']
            ]);

            // Notify Vendor
            $order->service->vendor->notify(new VendorNotification($order, 'Order Paid'));
            // Notify Client
            $order->user->notify(new ClientNotification($order, 'Successful Payment'));
            SendSms::dispatchAfterResponse($order->service->vendor->company_phone_number, 'The client completed the payment for the order '.$order->order_id);
         }
      }
   }

   /**
    * @param Request $request
    */
   public function b2cCallback(Request $request)
   {
      $callbackJSONData = file_get_contents('php://input');
      info($callbackJSONData);
   }

   public function confirmationCallback(Request $request)
   {
      $callbackJSONData = file_get_contents('php://input');
      $callbackData = json_decode($callbackJSONData);

      info($callbackJSONData);

      $response = Http::post('https://license.prisk.or.ke/prisk/api/confirmation/callback', ['data' => $callbackData]);

      info($response);

      // $invoice = Invoice::with('licenses', 'psvLicenses')->where('invoice_id', $result['payment_ref'])->first();

      // if ($invoice) {
      //    $payment = Payment::create([
      //          'user_id' => $invoice->user_id,
      //          'invoice_id' => $invoice->id,
      //          'amount' => $result['amount'],
      //          'payment_method' => 'Mpesa',
      //          'transaction_id' => $result['transaction_id']
      //    ]);

      //    if ((int) $result['amount'] == ((int) $invoice->getDiscountedPrice() - (int) $invoice->payments->sum('amount'))) {
      //          $invoice->status = 'Paid';
      //          $invoice->save();

      //          $licenses = $invoice->licenses->count() > 0 ? $invoice->licenses : $invoice->psvLicenses;

      //          foreach ($licenses as $key => $license) {
      //             $license->update([
      //                'valid_until' => now()->addYear()
      //             ]);
      //          }
      //    } else {
      //          $PSV = ['CSOK-03'];
      //          $Broadcaster = ['CSOK-19', 'CSOK-20', 'CSOK-21', 'CSOK-22'];

      //          if ($payment->invoice->licenses->count() > 0) {
      //             if (in_array($payment->invoice->licenses->first()->tariff->category, $Broadcaster)) {
      //                $payment['type'] = 'Broadcaster';
      //             } else {
      //                $payment['type'] = 'Business';
      //             }
      //          } else {
      //             $payment['type'] = 'PSV';
      //          }
      //          // Issue Receipt and send updated invoice and receipt to email
      //          $receipt_pdf = PDF::loadView("receipt.receipt", compact('payment'));
      //          $invoice_pdf = PDF::loadView("Invoices.invoiceMelanie", compact('invoice'));

      //          SendDocuments::dispatchAfterResponse($invoice->user, 'Invoice and Receipt', NULL, $invoice_pdf->output(), $receipt_pdf->output());
      //    }

      //    activity()
      //          ->causedBy($invoice->user)
      //          ->log($invoice->user->first_name.' '.$invoice->user->last_name.' paid for the Invoice #'.$invoice->invoice_id);
      // } else {
      //    $member = Member::where('tracking_number', $result['payment_ref'])->first();

      //    if ($member) {
      //          $registration_payment = RegistrationPayment::updateOrInsert(
      //             [
      //                'member_id' => $mpesa_payment->member->id
      //             ],
      //             [
      //                'balance' => (int) config('services.membership.registration_amount') - (int) $result['amount'],
      //             ],
      //          );
      //    }
      // }
   }

   public function validationCallback(Request $request)
   {
      info($request->all());
   }
}
