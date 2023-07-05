<main id="main" class="site-main">
   <div class="site-content owner-content vendor-orders">
      <div class="container">
         <div class="member-place-wrap">
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-12">
                  <div class="member-wrap-top">
                     <h2>Orders</h2>
                  </div><!-- .member-wrap-top -->
               </div>
               <div class="col-lg-6 col-md-6 col-sm-12">
                  <a class="orders-link" href="{{ route('vendor.orders.archived') }}">
                     <button class="btn">View Archived Orders</button>
                  </a>
               </div>
            </div>
            <form action="#" method="GET" id="orders-filters-form">
               <div class="row">
                  <div class="col-sm-12 col-md-4 col-lg-3">
                     <label for="" class="form-label">Search for Orders</label>
                     <input wire:model="search" type="text" id="service-title-search" name="s" placeholder="Enter Order ID" class="form-control">
                  </div>
                  <div class="col-sm-12 col-md-4 col-lg-3">
                     <label for="" class="form-label">Filter by Order Status</label>
                     <select name="place_cities" class="form-control" wire:model="status">
                        <option value="" disabled selected>Select Order Status</option>
                        <option value="All">All</option>
                        <option value="Paid">Paid</option>
                        <option value="Sent">Sent</option>
                        <option value="Received">Received</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Completed">Completed</option>
                        <option value="Dispute">Disputed</option>
                        <option value="Cancelled">Cancelled</option>
                     </select>
                  </div>
                  <div class="col-sm-12 col-md-4 col-lg-3">
                     <label for="" class="form-label">Filter by timeframe</label>
                     <select name="place_cities" class="form-control" wire:model="timeframe">
                        <option value="" disabled selected>Orders Made In The</option>
                        <option value="last_year">Last Year To Date</option>
                        <option value="last_month">Last Month To Date</option>
                        <option value="last_week">Last Week To Date</option>
                        <option value="last_two_years">Last 2 Years To Date</option>
                        <option value="last_four_years">Last 4 Years To Date</option>
                     </select>
                  </div>
                  <div class="col-sm-12 col-md-4 col-lg-3">
                     <label for="" class="form-label">Filter by Services Categories</label>
                     <select name="place_cities" class="form-control" wire:model="category">
                        <option value="" disabled selected>Select Service Category</option>
                        <option value="">All Service Categories</option>
                        @foreach ($categories as $category)
                           <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
            </form>
            <table class="member-place-list owner-booking table-responsive">
                  <thead>
                     <tr>
                        <th>ID</th>
                        <th class="table-width-150">Client's Name</th>
                        <th class="table-width-150">Date Due</th>
                        <th class="table-width-200">Service</th>
                        <th>Pricing</th>
                        <th class="table-width-50">Status</th>
                        <th>View Order</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach ($vendor->orders as $order)
                        <tr>
                           <td data-title="ID">
                              <a href="{{ route('vendor.orders.one', $order) }}">
                                 {{ $order->order_id }}
                              </a>
                           </td>
                           <td data-title="Client's Name" class="table-width-150">
                              <b>{{ $order->user->f_name }} {{ $order->user->l_name }}</b>
                           </td>
                           <td class="table-width-150" data-title="Date Due">{{ $order->event ? $order->event->event_start_date->diffForHumans() : "Not Set" }}</td>
                           <td class="table-width-200" data-title="Service">{{ $order->service->service_title }}</td>
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
                           <td data-title="Status" class="table-width-50">{{ $order->status }}</td>
                           <td>
                              <a href="{{ route('vendor.orders.one', $order) }}" class="view" title="View">View Order</a>
                           </td>
                        </tr>
                     @endforeach
                  </tbody>
            </table>
         </div><!-- .member-place-wrap -->
      </div>
   </div><!-- .site-content -->
</main><!-- .site-main -->
