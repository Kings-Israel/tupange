<div class="ob-item" wire:poll.5000ms="getNotificationCount">
   <div class="ob-head">
      <h3>Notifications <span>({{ $notificationCount }})</span></h3>
      <a href="#" class="clear-all" wire:click="markAllAsRead" title="Clear All">Clear all</a>
   </div>
   <div class="ob-content" wire:poll.5000ms="getNotifications">
      <ul>
         @foreach ($notifications as $notification)
            @if ($notification->data['type'] == 'Get Quote')
               <li class="noti-item unread">
                     <p>{{ $notification->data['client'] }} requested for a quote for the order, Order ID: <span style="cursor: pointer; font-size: 15px" wire:click.prevent="goToOrder('{{ $notification->id }}')">#{{ $notification->data['order'] }}</span></p>
                     <p>Service: {{ $notification->data['service'] }}</p>
                  <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" class="delete-noti">Mark as read</a>
               </li>
            @elseif($notification->data['type'] == 'Order Paid')
               <li class="noti-item unread">
                     <p>{{ $notification->data['client'] }} paid for the order, Order ID: <span style="cursor: pointer; font-size: 15px" wire:click.prevent="goToOrder('{{ $notification->id }}')">#{{ $notification->data['order'] }}</span></p>
                     <p>Service: {{ $notification->data['service'] }}</p>
                  <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" class="delete-noti">Mark as read</a>
               </li>
            @elseif($notification->data['type'] == 'Order Cancelled')
               <li class="noti-item unread">
                     <p>{{ $notification->data['client'] }} cancelled the order, Order ID: <span style="cursor: pointer; font-size: 15px" wire:click.prevent="goToOrder('{{ $notification->id }}')">#{{ $notification->data['order'] }}</span></p>
                     <p>Service: {{ $notification->data['service'] }}</p>
                  <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" class="delete-noti">Mark as read</a>
               </li>
            @elseif($notification->data['type'] == 'Accept Quotation')
               <li class="noti-item unread">
                     <p>{{ $notification->data['client'] }} accepted a quotation for the order, Order ID: <span style="cursor: pointer; font-size: 15px" wire:click.prevent="goToOrder('{{ $notification->id }}')">#{{ $notification->data['order'] }}</span></p>
                     <p>Service: {{ $notification->data['service'] }}</p>
                  <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" class="delete-noti">Mark as read</a>
               </li>
            @elseif($notification->data['type'] == 'Decline Quotation')
               <li class="noti-item unread">
                     <p>{{ $notification->data['client'] }} declined a quotation for the order, Order ID: <span style="cursor: pointer; font-size: 15px" wire:click.prevent="goToOrder('{{ $notification->id }}')">#{{ $notification->data['order'] }}</span></p>
                     <p>Service: {{ $notification->data['service'] }}</p>
                  <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" class="delete-noti">Mark as read</a>
               </li>
            @elseif($notification->data['type'] == 'Order Completed')
               <li class="noti-item unread">
                     <p>{{ $notification->data['client'] }} marked the order, Order ID: <span style="cursor: pointer; font-size: 15px" wire:click.prevent="goToOrder('{{ $notification->id }}')">#{{ $notification->data['order'] }}</span> as completed</p>
                     <p>Service: {{ $notification->data['service'] }}</p>
                  <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" class="delete-noti">Mark as read</a>
               </li>
            @elseif($notification->data['type'] == 'Order Dispute')
               <li class="noti-item unread">
                     <p>{{ $notification->data['client'] }} raised a dispute for the order, Order ID: <span style="cursor: pointer; font-size: 15px" wire:click.prevent="goToOrder('{{ $notification->id }}')">#{{ $notification->data['order'] }}</span></p>
                     <p>Service: {{ $notification->data['service'] }}</p>
                  <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" class="delete-noti">Mark as read</a>
               </li>
             @elseif($notification->data['type'] == 'Order Dispute Resolved')
               <li class="noti-item unread">
                     <p>{{ $notification->data['client'] }} marked the dispute as resolved for the order, Order ID: <span style="cursor: pointer; font-size: 15px" wire:click.prevent="goToOrder('{{ $notification->id }}')">#{{ $notification->data['order'] }}</span></p>
                     <p>Service: {{ $notification->data['service'] }}</p>
                  <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" class="delete-noti">Mark as read</a>
               </li>
            @endif
         @endforeach
      </ul>
   </div>
</div>
