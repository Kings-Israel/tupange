<div>
   <div class="member-filter">
      <div class="mf-left">
         <div class="field-select">
            <select name="place_cities" wire:model="perPage">
               <option value="1">Show 1</option>
               <option value="5">Show 5</option>
               <option value="10">Show 10</option>
               <option value="20">Show 20</option>
               <option value="40">Show 40</option>
            </select>
            <i class="la la-angle-down"></i>
         </div>
         <div class="field-select">
            <select name="place_cities" wire:model="status">
               <option value="All">All</option>
               <option value="Sent">Sent</option>
               <option value="Received">Received</option>
               <option value="Delivered">Delivered</option>
               <option value="Completed">Completed</option>
               <option value="Declined">Declined</option>
               <option value="Dispute">Disputed</option>
            </select>
            <i class="la la-angle-down"></i>
         </div>
         <div class="field-input">
            <input type="text" placeholder="Search Order, Service, Event..." wire:model="search" width="100%">
         </div>
      </div><!-- .mf-left -->
   </div><!-- .member-filter -->
   <table class="member-place-list owner-booking table-responsive">
      <thead>
         <tr>
            <th class="table-width-50">#</th>
            <th class="table-width-150">
               @include('partials.sort-link', ['label' => 'ID', 'attribute' => 'id', 'url' => '/client/orders'])
            </th>
            <th class="table-width-150">
               @include('partials.sort-link', ['label' => 'Service', 'attribute' => 'service', 'url' => '/client/orders'])
            </th>
            <th class="table-width-200">
               @include('partials.sort-link', ['label' => 'Order Made', 'attribute' => 'created_at', 'url' => '/client/orders'])
            </th>
            <th class="table-width-50">Event</th>
            <th class="table-width-150">
               @include('partials.sort-link', ['label' => 'Status', 'attribute' => 'status', 'url' => '/client/orders'])
            </th>
            <th class="table-width-150">Pricing</th>
            <th class="table-width-50"></th>
         </tr>
      </thead>
      <tbody>
         <form action="{{ route('orders.checkout') }}" method="POST">
            @csrf
            @foreach ($orders as $order)
               @include('partials.client-delete-order')
               <tr>
                  <td class="table-width-50">
                     @if ($order->status == 'Received')
                        <label>
                           <input type="checkbox" wire:model.defer="selectedOrders.{{ $order->id }}" name="orders[{{ $order->id }}]" value="{{ $order->id }}" onchange="selectOrder(this)">
                        </label>
                     @endif
                     @if($order->status == 'Sent')
                        <label>
                           <i class="las la-info-circle" title="You can pay for the order once received by the vendor."></i>
                        </label>
                     @endif
                  </td>
                  <td data-title="Order ID" class="table-width-150">
                     <a href="{{ route('client.orders.order', $order) }}">
                        {{ $order->order_id }}
                     </a>
                  </td>
                  <td data-title="Service" class="table-width-150"><b>{{ $order->service->service_title }}</b></td>
                  {{-- <td data-title="Vendor" class="table-width-150">{{ $order->service->vendor->company_name }}</td> --}}
                  <td data-title="Order Made" class="table-width-200">{{ $order->created_at->format('d M, Y') }}</td>
                  <td data-title="Linked Event" class="table-width-50">
                     @if ($order->event_id)
                        <a href="{{ route('events.show', $order->event_id) }}">
                           {{ $order->event->event_name }}
                        </a>
                     @else
                        None
                     @endif
                  </td>
                  <td data-title="Status"><span class="table-width-150 order-{{ $order->status }}">{{ $order->status }}</span></td>
                  @if ($order->payment()->count() > 0)
                     <td class="table-width-150" data-title="Order Price">{{ $order->order_quotation ? $order->order_quotation->order_pricing_title : $order->service_pricing->service_pricing_title }}<strong style="display: flex">(Ksh.<p>{{ number_format($order->payment->amount) }}</p>)</strong></td>
                  @else
                     @if ($order->order_quotation)
                        <td class="table-width-150" data-title="Order Price">{{ $order->order_quotation->order_pricing_title }} <strong style="display: flex">(Ksh.<p>{{ number_format($order->order_quotation->order_pricing_price) }}</p>)</strong></td>
                     @elseif($order->service_pricing)
                        <td class="table-width-150" data-title="Service Price">{{ $order->service_pricing->service_pricing_title }} <strong style="display: flex">(Ksh.<p>{{ number_format($order->service_pricing->service_pricing_price) }}</p>)</strong></td>
                     @else
                        <td class="table-width-150" data-title="Quotation">Get Quote</td>
                     @endif
                  @endif
                  <td class="table-width-50">
                     <a href="" class="order-options-mobile" data-bs-toggle="modal" data-bs-target="#client-delete-order-{{ $order->id }}"><i class="la la-trash-alt"></i></a>
                     <div class="order-options-desktop" x-data="{ isOpen: false }">
                        <i class="fa fa-angle-down" @click="isOpen = !isOpen"></i>
                        <div
                           x-cloak
                           x-show="isOpen"
                           @click.away="isOpen = false"
                           @keydown.escape.window = "isOpen = false"
                           class="search-results"
                        >
                           <ul>
                              @if ($order->service_pricing || $order->order_quotation)
                                 @if ($order->status == 'Received')
                                    <a class="result-link" href="{{ route('client.order.pay.link', $order->id) }}">
                                       <li class="result">
                                          <i class="la la-money"></i>Pay
                                       </li>
                                    </a>
                                 @endif
                              @endif
                              <a href="" @click="isOpen = false" data-bs-toggle="modal" data-bs-target="#client-delete-order-{{ $order->id }}" class="result-link">
                                 <li class="result">
                                    <i class="la la-trash-alt"></i>Delete
                                 </li>
                              </a>
                              <a href="{{ route('message.chat', $order->order_id) }}" class="result-link">
                                 <li class="result">
                                    <i class="fas fa-envelope"></i>Chat with Vendor
                                 </li>
                              </a>
                              @if ($order->status == 'Sent' || $order->status == 'Received' || $order->status == 'Declined')
                                 <a href="{{ route('client.order.cancel', $order) }}" class="result-link">
                                    <li class="result">
                                       <i class="las la-times la-24-black"></i>Cancel Order
                                    </li>
                                 </a>
                              @endif
                           </ul>
                        </div>
                     </div>
                  </td>
               </tr>
            @endforeach
            <div class="client-checkout-btn d-flex justify-content-between" style="width:40%">
               <div>
                  <a href="{{ route('client.services.all') }}" class="btn" style="background-color: #1DA1F2">
                     <i class="la la-plus"></i>Add Service
                  </a>
               </div>
               <div>
                  <input type="submit" value="Checkout" class="btn" id="checkout-btn" hidden>
                  <span id="select-order-text"><strong>Select Received Orders to Checkout</strong></span>
               </div>
            </div>
         </form>
      </tbody>
   </table>
   {{ $orders->links() }}

   @push('scripts')
      <script>
         let service_pricings = $('[id=service_pricing_view]')
         service_pricings.each((ind, obj) => {
            var num = obj.innerHTML.replace(/,/gi, "");
            var num2 = num.split(/(?=(?:\d{3})+$)/).join(",")
            obj.innerHTML = num2
         });

         var orders = []
         function selectOrder(e) {
            if (orders.includes(e.value)) {
               let index = orders.findIndex(order => order == e.value)
               orders.splice(index, 1)
            } else {
               orders.push(e.value)
            }
            if (orders.length > 0) {
               $('#checkout-btn').removeAttr('hidden')
               $('#select-order-text').attr('hidden', 'hidden')
            } else {
               $('#checkout-btn').attr('hidden', 'hidden')
               $('#select-order-text').removeAttr('hidden')
            }
         }
      </script>
   @endpush
</div>
