<?php

namespace App\Http\Controllers;

use App\Jobs\SendOrderUpdateMail;
use App\Jobs\SendSms;
use App\Models\Cart;
use App\Models\Messages;
use App\Models\Order;
use App\Models\ServicePricing;
use App\Notifications\VendorNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
   public function __construct()
   {
      $this->middleware(['auth', 'auth.session']);
   }

   public function cart()
   {
      return view('client.cart');
   }

   public function showOrders()
   {
      return view('client.orders');
   }

   public function getPricingPrice($id)
   {
      return $price = (int) ServicePricing::where('id', $id)->pluck('service_pricing_price')->first();
   }

   public function checkout(Request $request)
   {
      if (!$request->service) {
         return back()->with('error', "Please add items to cart before checkout");
      }

      foreach ($request->service as $service) {
         $phoneNumberCheck = preg_match('/(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?/', preg_replace('/\s+/', '', $request->order_message[$service]));
         $emailCheck = preg_match('/(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/', preg_replace('/\s+/', '', $request->order_message[$service]));
         if($phoneNumberCheck != false) {
            return back()->withInput()->with('error', 'Do not share phone numbers.');
         }
         if($emailCheck != false) {
            return back()->withInput()->with('error', 'Do not share email addresses.');
         }
         $orders = Order::all()->pluck('order_id');
         $order_id = Str::upper(Str::random(2)).time().Str::upper(Str::random(2));
         while ($orders->contains($order_id)) {
            $order_id = Str::upper(Str::random(2)).time().Str::upper(Str::random(2));
         }
         $order = auth()->user()->orders()->create([
               'order_id' => $order_id,
               'event_id' => $request->event_id,
               'service_id' => $service,
               'service_pricing_id' => $request->service_pricing[$service],
               'message' => $request->order_message[$service]
            ]);

         // Forget event_id if set in session
         session()->forget('event_id');

         // Delete from cart
         Cart::where('user_id', auth()->user()->id)->where('service_id', $service)->delete();

         if (! $order->service_pricing) {
            $order->service->vendor->notify(new VendorNotification($order, 'Get Quote'));

            // Send Email to vendor
            $data = [];
            $data['email'] = $order->service->vendor->company_email;
            $data['subject'] = 'Request for custom quoation for the service '.$order->service->service_title;
            $data['content'] = 'An order was made one of the services you offer and the client requests for a custom quotation. Please login below to view the order.';

            SendOrderUpdateMail::dispatchAfterResponse($data);
         }

         // Send message to vendor
         if ($order->message != NULL) {
            // Get receiver
            if ($order->user_id == auth()->user()->id) {
               $receiver = $order->service->vendor->user_id;
            } elseif($order->service->vendor->user_id == auth()->user()->id) {
               $receiver = $order->user_id;
            }

            $send = new Messages();
            $send->order_id = $order->order_id;
            $send->message = $order->message;
            $send->sent_to = $receiver;
            $send->sent_from = auth()->user()->id;
            $send->is_read = false;
            $send->save();
         }

         // Send SMS to vendor
         SendSms::dispatchAfterResponse($order->service->vendor->company_phone_number, 'A new order has been made for the service '.$order->service->service_title);
      }

      if (session('cart')) {
         session()->forget('cart');
      }

      return redirect()->route('client.orders')->with('success', 'Order completed');
   }
}
