<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\Models\User;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\Service;
use App\Models\Messages;
use App\Events\NewMessage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class MessagesController extends Controller
{
   public function __construct()
   {
      $this->middleware(['auth', 'auth.session']);
   }
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      $orders = Messages::where('sent_to', auth()->user()->id)
                        ->join('orders','orders.order_id','=','messages.order_id')
                        ->join('services','services.id','=','orders.service_id')
                        ->groupby('messages.order_id')
                        ->get();

      return view('messages.index', compact('orders'));
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function chats($code)
   {
      $orders = Messages::join('orders','orders.order_id','=','messages.order_id')
                        ->join('services','services.id','=','orders.service_id')
                        ->groupby('messages.order_id')
                        ->get();

      $messages = Messages::where('messages.order_id',$code)->orderby('id', 'asc')->get();

      foreach ($messages as $message) {
         if ($message->sent_to == auth()->user()->id) {
            $message->is_read = true;
            $message->save();
         }
      }

      $orderDetails = Order::with('service')->where('order_id', $code)->first();

      if (!$orderDetails) {
         return view('messages.index', compact('orders'))->with('error', 'The order was deleted.');
      }

      return view('messages.chats', compact('orders','messages','orderDetails'));
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      $this->validate($request,[
         'message' => 'required',
      ]);

      $order = Order::where('order_id', $request->order_id)->first();

      if(!$order) {
         return response()->json('This order was deleted', 404);
      }

      // Get receiver
      if ($order->user_id == auth()->user()->id) {
         $receiver = $order->service->vendor->user_id;
      } elseif($order->service->vendor->user_id == auth()->user()->id) {
         $receiver = $order->user_id;
      }

      $send = new Messages;
      $send->order_id = $request->order_id;
      $send->message = $request->message;
      $send->sent_to = $receiver;
      $send->sent_from = Auth::user()->id;
      $send->is_read = false;
      $send->save();

      if ($order->user_id == auth()->user()->id) {
         // Broadcast to vendor
         event(new NewMessage($order->service->vendor->user->email, $order->order_id, $order->user, $request->message));
      } elseif($order->service->vendor->user_id == auth()->user()->id) {
         // Broadcast to client
         event(new NewMessage($order->user->email, $order->order_id, $order->service->vendor->user, $request->message));
      }

      Session::flash('success','Message sent');

      return response()->json('Message Sent', 200);
   }

   /**
    * Display the specified resource.
    *
    * @param  \App\Models\Messages  $messages
    * @return \Illuminate\Http\Response
    */
   public function show(Messages $messages)
   {
      //
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Messages  $messages
    * @return \Illuminate\Http\Response
    */
   public function edit(Messages $messages)
   {
      //
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Messages  $messages
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, Messages $messages)
   {
      //
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Messages  $messages
    * @return \Illuminate\Http\Response
    */
   public function destroy(Messages $messages)
   {
      //
   }

   public function getUserName(Request $request)
   {
      preg_match('~@(.*?)@~', $request->username, $output);
      $user = User::where('f_name', 'LIKE', '%'.$output[1].'%')->orWhere('l_name', 'LIKE', '%'.$output[1].'%')->first();

      return response()->json($user, 200);
   }
}
