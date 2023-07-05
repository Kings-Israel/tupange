<div>
   @if ($order->service_pricing || $order->order_quotation)
      @if ($order->status == 'Sent')
         <button class="btn order-status-btn" style="background: green" wire:click="receiveOrder({{ $order }})">Accept Order</button>
         <button class="btn order-status-btn" style="background: red" wire:click="declineOrder({{ $order }})">Decline</button>
      @elseif($order->status == 'Received')
         {{-- <button class="btn" style="background: red" wire:click="cancelOrder({{ $order }})">Cancel Order</button> --}}
         <button data-bs-toggle="modal" data-bs-target="#cancel-order-{{ $order->id }}" class="btn order-status-btn" style="background: red">Cancel Order</button>
      @elseif ($order->status == 'Delivered')
         <button class="btn order-status-btn" style="background: green" wire:click="completeOrder({{ $order }})">Completed</button>
      @elseif ($order->status == 'Completed')
         <button class="btn order-status-btn" style="background: green" wire:click="archiveOrder({{ $order }})">Archive</button>
      @endif
   @elseif($order->status == 'Received')
      {{-- <button class="btn" style="background: red" wire:click="cancelOrder({{ $order }})">Cancel Order</button> --}}
      <button data-bs-toggle="modal" data-bs-target="#cancel-order-{{ $order->id }}" class="btn order-status-btn" style="background: red">Cancel Order</button>
   @endif
   <div wire:loading>
      <x-loader></x-loader>
   </div>
</div>
