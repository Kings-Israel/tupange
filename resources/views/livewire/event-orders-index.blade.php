<div>
    <main id="main" class="site-main">
        <div class="site-content owner-content">
            <div class="container">
                <div class="member-place-wrap">
                    <div class="member-wrap-top">
                        <h2>{{ $event->event_name }}'s Orders</h2>
                        <div class="member-wrap-top">
                           @can('viewOrders', $event)
                              <a class="btn mt-1" style="background-color: black" href="{{ route('client.event.service.order', $event->id) }}">
                                 Order Service
                              </a>
                           @endcan
                           <a class="btn mt-1" href="{{ route('events.show', $event) }}">Back to My Event</a>
                        </div>

                    </div><!-- .member-place-wrap -->
                    <table class="member-place-list owner-booking table-responsive">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th class="table-width-200">Service</th>
                                <th>Pricing</th>
                                <th>Order Price</th>
                                <th>Status</th>
                                <th>Ordered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders->orders as $order)
                                <tr>
                                    @include('partials.delete-order')
                                    <td data-title="Order ID">
                                        <a href="{{ route('client.orders.order', $order) }}">
                                            <p>{{ $order->order_id }}</p>
                                        </a>
                                    </td>
                                    <td data-title="Service Category" class="table-width-200">
                                        {{$order->service->service_title }}
                                    </td>
                                    <td data-title="Order Pricing">
                                        <span>{{ $order->service_pricing_id ? $order->service_pricing->service_pricing_title : 'No Price selected' }}</span>
                                    </td>
                                    <td data-title="Price">
                                       @if ($order->payment()->count() > 0)
                                          <span style="display: flex">Ksh. {{ number_format($order->payment->amount) }}</span>
                                       @else
                                          @if ($order->service_pricing)
                                             <span style="display: flex">Ksh. {{ number_format($order->service_pricing->service_pricing_price) }}</span>
                                          @elseif($order->order_quotation)
                                             <span style="display: flex">Ksh. {{ number_format($order->order_quotation->order_pricing_price) }}</span>
                                          @else
                                             <p>Ksh. 0</p>
                                          @endif
                                       @endif
                                    </td>
                                    <td data-title="Status">
                                       <span class="order-{{ $order->status }}">{{ $order->status }}</span>
                                    </td>
                                    <td data-title="Order was made">
                                        <span>{{ $order->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td>
                                       @if ($order->status == 'Sent' || $order->status == 'Received')
                                          <button class="btn btn-sm" data-bs-toggle="modal" data-bs-target="#delete-order-{{ $order->id }}" style="cursor: pointer" style="background: red;">
                                             Cancel
                                          </button>
                                       @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                     </table>
                </div><!-- .member-wrap-top -->
            </div>
        </div><!-- .site-content -->
    </main><!-- .site-main -->
    @push('scripts')
        <script>
           let event_number = $('[id=event_number_view]')
            event_number.each((ind, obj) => {
               var num = obj.innerHTML.replace(/,/gi, "");
               var num2 = num.split(/(?=(?:\d{3})+$)/).join(",")
               obj.innerHTML = num2
            });
        </script>
    @endpush
</div>
