<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessage implements ShouldBroadcast
{
   use Dispatchable, InteractsWithSockets, SerializesModels;

   public $email;
   public $sender;
   public $order;
   public $message;

   /**
    * Create a new event instance.
    *
    * @return void
    */
   public function __construct($email, $order, $sender, $message)
   {
      $this->email = $email;
      $this->sender = $sender;
      $this->order = $order;
      $this->message = $message;
   }

   /**
    * Get the channels the event should broadcast on.
    *
    * @return \Illuminate\Broadcasting\Channel|array
    */
   public function broadcastOn()
   {
      return new Channel(''.$this->email.'');
   }

   public function broadcastAs()
   {
      return 'new.message';
   }

   public function broadcastWith()
   {
      return [
         'order' => $this->order,
         'user' => $this->sender,
         'message' => $this->message
      ];
   }
}
