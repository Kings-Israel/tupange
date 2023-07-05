<main id="main" class="site-main">
   <div class="site-content owner-content">
       <div class="container">
          <div class="member-place-wrap">
               <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-12">
                     <div class="member-wrap-top">
                        <h2>Reviews</h2>
                     </div><!-- .member-wrap-top -->
                 </div>
              </div>
               <form action="#" method="GET" id="orders-filters-form">
                  <div class="row">
                     <div class="col-sm-12 col-md-7 col-lg-4">
                        <label for="" class="form-label">Search for Reviews</label>
                        <input wire:model="search" type="text" id="service-title-search" name="s" placeholder="Enter Order ID or Service" class="form-control">
                     </div>
                     <div class="col-sm-12 col-md-5 col-lg-8">
                        <a href="{{ route('vendor.dashboard') }}" class="btn link-to-dashboard">Go To Dashboard</a>
                     </div>
                  </div>
               </form>
               <table class="member-place-list owner-booking table-responsive">
                   <thead>
                       <tr>
                           <th>Order ID</th>
                           <th class="">Review</th>
                           <th>Rating</th>
                           <th class="">Service</th>
                           <th class="">Client's Name</th>
                       </tr>
                   </thead>
                   <tbody>
                       @foreach ($vendor->reviews as $review)
                           <tr>
                              <td data-title="Order ID">
                                 {{ $review->order->order_id }}
                              </td>
                              <td data-title="Review">
                                 {{ $review->comment }}
                              </td>
                              <td data-title="Rating">
                                 {{ ((int)$review->rating/5) * 5 }}/5
                              </td>
                              <td data-title="Service">
                                 {{ $review->order->service->service_title }}
                              </td>
                              <td data-title="Client's Name" class="">
                                 <b>{{ $review->user->f_name }} {{ $review->user->l_name }}</b>
                              </td>
                           </tr>
                       @endforeach
                   </tbody>
               </table>
           </div><!-- .member-place-wrap -->
       </div>
   </div><!-- .site-content -->
</main><!-- .site-main -->

