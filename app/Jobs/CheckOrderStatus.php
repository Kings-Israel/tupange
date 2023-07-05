<?php

namespace App\Jobs;

use App\Models\Order;
use App\Notifications\ClientNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckOrderStatus implements ShouldQueue
{
   use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   /**
    * Create a new job instance.
    *
    * @return void
    */
   public function __construct()
   {
      //
   }

   /**
    * Execute the job.
    *
    * @return void
    */
   public function handle()
   {
      $orders = Order::where('status', 'Paid')->get();

      foreach($orders as $order) {
         // Send reminder to client to mark the order a complete
         if (now()->diffInDays($order->updated_at) >= 3) {
            SendSms::dispatch($order->user->phone_number, 'This is a reminder to mark the order, Order ID '.$order->order_id.' as delivered on tupange.com.');
            $order->user->notify(new ClientNotification($order, 'Order Delivered'));
         }

         // Send SMS notification reminder to client and vendor
         if ($order->event) {
            if (now()->diffInDays($order->event->event_start_date) == 1) {
               SendSms::dispatch($order->user->phone_number, 'Hello Client. This is a reminder that your order, Order ID: '.$order->order_id.' is set to be delivered tomorrow. Regards Tupange.com.');
               SendSms::dispatch($order->service->vendor->company_phone_number, 'Hello Vendor. This is a reminder that the order, Order ID: '.$order->order_id.' is set to be delivered tomorrow. Regards Tupange.com.');
            }
         }
      }
   }
}
