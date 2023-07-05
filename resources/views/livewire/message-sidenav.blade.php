<div>
   <div id="search">
      {{-- <label for=""><i class="fa fa-search" aria-hidden="true"></i></label> --}}
      {{-- <input type="text" placeholder="Search orders..." wire:model="search" style="width: 100%" /> --}}
   </div>
   <div id="contacts">
      <ul>
         @forelse($orders as $order)
            <a href="{!! route('message.chat',$order->order_id) !!}">
               <li class="contact">
                  <div class="wrap">
                     <div class="meta">
                        <p class="name text-white">{!! $order->order_id !!}</p>
                        <p class="preview text-white">{!! $order->order ? $order->order->service->service_title : '' !!}</p>
                        @if ($order->hasUnreadMessages())
                           <i class="fas fa-circle" style="color: #8FCA27; font-size: 8px; float: right; margin-top: -30px"></i>
                        @endif
                     </div>
                     <div class="chat-service-mobile-view">
                        <img src="https://ui-avatars.com/api/?name={!! $order->order ? $order->order->service->service_title : 'Service' !!}&rounded=true&size=60" alt="" />
                     </div>
                  </div>
               </li>
            </a>
            @empty
            <p class="no-conversation-text-side">No Conversations Yet...</p>
               @if (auth()->user()->status === 'Vendor')
                  <p class="no-conversation-text-side">Select an <a class="orders-link" href="{{ route('vendor.orders.all') }}">Order</a> to start chatting with clients</p>
               @else
                  <p class="no-conversation-text-side">Select an <a class="orders-link" href="{{ route('client.orders') }}">Order</a> to start chatting with vendors</p>
               @endif
            <p></p>
         @endforelse
      </ul>
   </div>
</div>
