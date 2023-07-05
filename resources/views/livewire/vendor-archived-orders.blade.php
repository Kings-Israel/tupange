<main id="main" class="site-main">
   <div class="site-content owner-content vendor-orders">
      <div class="container">
         <div class="member-place-wrap">
            <form action="#" method="GET" id="orders-filters-form">
               <div class="row">
                  <div class="col-sm-12 col-md-4 col-lg-3">
                     <label for="" class="form-label">Search for Orders</label>
                     <input wire:model="search" type="text" id="service-title-search" name="s" placeholder="Enter Order ID" class="form-control">
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
                           @if ($order->service_pricing)
                           <td data-title="Pricing">{{ $order->service_pricing->service_pricing_title }} <strong style="display: flex">(Ksh. <p id="order_money_view">{{ $order->service_pricing->service_pricing_price }}</p>)</strong></td>
                           @elseif($order->order_quotation)
                           <td data-title="Pricing">{{ $order->order_quotation->order_pricing_title }} <strong style="display: flex">(Ksh. <p id="order_money_view">{{ $order->order_quotation->order_pricing_price }}</p>)</strong></td>
                           @else
                           <td data-title="Pricing">Get Quote</td>
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
   @push('scripts')
       <script>
         let event_number = $('[id=order_money_view]')
         event_number.each((ind, obj) => {
            var num = obj.innerHTML.replace(/,/gi, "");
            var num2 = num.split(/(?=(?:\d{3})+$)/).join(",")
            obj.innerHTML = num2
         });
       </script>
   @endpush
</main><!-- .site-main -->
