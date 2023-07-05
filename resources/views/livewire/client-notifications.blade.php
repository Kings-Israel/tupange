<li>
   @if (Route::currentRouteName() == 'order.checkout')
      <i class="las la-bell" style="font-size: 18px; cursor: pointer"></i>({{ $notificationCount }})
      <ul class="sub-menu">
   @else
      <i class="las la-bell" style="font-size: 18px; cursor: pointer" wire:poll.10000ms="getNotificationCount"></i>({{ $notificationCount }})
      <ul class="sub-menu" wire:poll.5000ms="getNotifications">
   @endif
      @if ($notifications->count())
         @foreach ($notifications as $notification)
            @if ($notification->data['type'] == 'Added Quote')
               <li>
                  <p>{{ $notification->data['vendor'] }} added a quote for the order, Order ID: <span style="cursor: pointer; font-size: 15px; color: #1772AB" wire:click.prevent="goToOrder('{{ $notification->id }}')">#{{ $notification->data['order'] }}</span></p>
                  <p>Service: {{ $notification->data['service'] }}</p>
                  <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" class="delete-noti">Mark as read</a>
               </li>
            @endif
            @if($notification->data['type'] == 'Order Received')
               <li>
                  <p>{{ $notification->data['vendor'] }} accepted the order, Order ID: <span style="cursor: pointer; font-size: 15px; color: #1772AB" wire:click.prevent="goToOrder('{{ $notification->id }}')">#{{ $notification->data['order'] }}</span></p>
                  <p>Service: {{ $notification->data['service'] }}</p>
                  <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" class="delete-noti">Mark as read</a>
               </li>
            @endif
            @if($notification->data['type'] == 'Order Declined')
               <li>
                  <p>{{ $notification->data['vendor'] }} declined the order, Order ID: <span style="cursor: pointer; font-size: 15px; color: #1772AB" wire:click.prevent="goToOrder('{{ $notification->id }}')">#{{ $notification->data['order'] }}</span></p>
                  <p>Service: {{ $notification->data['service'] }}</p>
                  <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" class="delete-noti">Mark as read</a>
               </li>
            @endif
            @if($notification->data['type'] == 'Order Cancelled')
               <li>
                  <p>{{ $notification->data['vendor'] }} cancelled the order, Order ID: <span style="cursor: pointer; font-size: 15px; color: #1772AB" wire:click.prevent="goToOrder('{{ $notification->id }}')">#{{ $notification->data['order'] }}</span></p>
                  <p>Service: {{ $notification->data['service'] }}</p>
                  <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" class="delete-noti">Mark as read</a>
               </li>
            @endif
            @if($notification->data['type'] == 'Event Role')
               <li>
                  <p>{{ $notification->data['event_creator'] }} invited you to assist in planning the event, Event: <span style="cursor: pointer; font-size: 15px; color: #1772AB" wire:click.prevent="goToEvent('{{ $notification->id }}')">#{{ $notification->data['event_title'] }}</span>, with the role of {{ $notification->data['role'] }}</p>
                  <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" class="delete-noti">Mark as read</a>
               </li>
            @endif
            @if($notification->data['type'] == 'Successful Payment')
               <li>
                  <p>Payment for the order, Order ID: <span style="cursor: pointer; font-size: 15px; color: #1772AB" wire:click.prevent="goToOrder('{{ $notification->id }}')">#{{ $notification->data['order'] }}</span>, was successful.</p>
                  <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" class="delete-noti">Mark as read</a>
               </li>
            @endif
            @if($notification->data['type'] == 'Password Notification')
               <li>
                  <p>{{ $notification->data['email'] }} has been set as you password. Go to your <a href="#" wire:click.prevent="goToProfile('{{ $notification->id }}')" class="delete-noti" style="color: #1772AB">Profile</a> to change the password.</p>
                  <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" class="delete-noti">Mark as read</a>
               </li>
            @endif
            @if ($notification->data['type'] == 'Program Notification')
               <li>
                  <p>{{ $notification->data['message'] }}</p>
                  <a href="#" wire:click.prevent="goToProgram('{{ $notification->id }}')" class="delete-noti">Mark as read</a>
               </li>
            @endif
            @if($notification->data['type'] == 'Order Delivered')
               <li>
                  <p>Please mark the order, Order ID: <span style="cursor: pointer; font-size: 15px; color: #1772AB" wire:click.prevent="goToOrder('{{ $notification->id }}')">#{{ $notification->data['order'] }}</span>, as delivered.</p>
                  <a href="#" wire:click.prevent="markAsRead('{{ $notification->id }}')" class="delete-noti">Mark as read</a>
               </li>
            @endif
         @endforeach
         <span wire:click.prevent="markAllAsRead" style="cursor: pointer; padding-right: 5px;">Mark all as read</span>
      @else
         <div style="text-align: center">
            <span>No new notifications<span>
         </div>
      @endif
   </ul>
</li>

