<?php

namespace App\Http\Controllers;

use App\Jobs\SendSms;
use App\Models\Event;
use App\Models\Order;
use App\Models\Messages;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Models\OrderQuotations;
use App\Http\Controllers\Controller;
use App\Notifications\VendorNotification;

class OrderController extends Controller
{
   public function __construct()
   {
      $this->middleware(['auth', 'auth.session']);
   }

   public function index()
   {
      return view('orders.index');
   }

   public function viewArchivedOrders()
   {
      $vendor = auth()->user()->vendor;
      $orders = $vendor->load(['orders' => function($query) {
         return $query->where('orders.status', 'Archived');
      }]);
      $categories = $orders->orders->map(function ($order) {
         return $order->service->category;
      });
      return view('vendor.archived-orders')->with(['vendor' => $vendor, 'categories' => $categories->unique()]);
   }

   public function order(Order $order)
   {
      $order_quotations = OrderQuotations::where('order_id', $order->id)->where('status', '!=', 'Archived')->get();
      $events = [];
      if (! $order->event) {
         $events = Event::where('user_id', auth()->user()->id)->get();
      }

      // Attach Events with roles
      if (auth()->user()->hasAssignedRoles()) {
         $roles = UserRole::with('event')->where('user_id', auth()->id())->get();
         foreach ($roles as $role) {
            if($role->role_id == 1) {
               collect($events)->push(Event::find($role->event_id));
            }
         }
      }
      return view('client.order', compact('order', 'order_quotations', 'events'));
   }

   public function orderPay(Order $order)
   {
      $request = new Request([
         'orders' => [''.$order->id.'' => $order->id]
      ]);
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

   public function addEventToOrder(Request $request)
   {
      $order = Order::find($request->order_id);
      $order->event_id = $request->event;
      $order->save();

      return back()->with('success', 'Event added to order');
   }

   public function markOrderAsDelivered(Order $order)
   {
      $order->status = 'Completed';
      $order->save();
      $order->service->vendor->notify(new VendorNotification($order, 'Order Completed'));

      return back()->with('success', 'Order completed. Please leave a review below');
   }

   public function fileDispute(Request $request)
   {
      $order = Order::find($request->order_id);
      $order->status = 'Disputed';
      $order->status_reason = $request->description;
      $order->save();

      SendSms::dispatchAfterResponse($order->service->vendor->company_phone_number, 'A client raised a dispute for the Order ID: '.$order->order_id);

      $order->service->vendor->notify(new VendorNotification($order, 'Order Dispute'));

      return back()->with('success', 'Order status has been updated');
   }

   public function resolveDispute(Order $order)
   {
      $order->status = 'Completed';
      $order->status_reason = '';
      $order->save();

      SendSms::dispatchAfterResponse($order->service->vendor->company_phone_number, 'A client marked the dispute for Order ID: '.$order->order_id.' as resolved.');

      $order->service->vendor->notify(new VendorNotification($order, 'Order Dispute Resolved'));

      return back()->with('success', 'Order status updated to resolved');
   }

   public function cancelOrder(Order $order)
   {
      $order->status = 'Cancelled';
      $order->status_reason = '';
      $order->save();

      $order->service->vendor->notify(new VendorNotification($order, 'Order Cancelled'));

      return redirect()->route('client.orders')->with('success', 'The order has been cancelled');
   }

   public function deleteOrder(Order $order)
   {
      if ($order->review()->exists()) {
         $order->review()->delete();
      }

      // $order->messages()->delete();
      $messages = Messages::where('order_id', $order->order_id)->get();
      if ($messages) {
         collect($messages)->each(fn ($message) => $message->delete());
      }

      if ($order->status == 'Sent' || $order->status == 'Received') {
         $order->forceDelete();
      } else {
         $order->delete();
      }

      return redirect()->route('client.orders')->with('success', 'Order deleted successfully');
   }

}
