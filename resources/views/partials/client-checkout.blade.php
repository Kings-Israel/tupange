{{-- Check out Modal --}}
<div class="modal fade signup" wire:ignore.self id="checkout" tabindex="-1" role="dialog" aria-labelledby="checkout-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="">
         <div class="modal-body">
               <span>
                  <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                     <i class="las la-times la-24-black"></i>
                  </a><!-- .popup__close -->
                  <h2 class="modal-header-title">Checkout Order {{ $order->order_id }}</h2>
                  <hr>
                  <div class="container">
                     <h1>{{ $order->service->service_title }}</h1>
                     <p style="font-size: 15px; margin-bottom: 5px">{{ $order->service->getCategory($order->service->id)->name }}</p>
                     <h3 class="service-description" style="margin-bottom: 5px">{{ $order->service->service_description }}</h3>
                     Total Price: Ksh.<h4>{{ $order->service_pricing ? $order->service_pricing->service_pricing_price : $order->order_quotation->order_pricing_price }}</h4>
                     <hr>
                     <form action="{{ route('order.single.checkout', $order) }}" method="POST">
                           @csrf
                           <input type="text" hidden name="total_price" value="{{ $order->service_pricing ? $order->service_pricing->service_pricing_price : $order->order_quotation->order_pricing_price }}">
                           <div class="row">
                              <div class="col-lg-6">
                                 <input type="submit" value="Pay with Paypal" class="btn">
                              </div>
                              <div class="col-lg-6">
                                 @include('partials.mpesa')
                                 <input type="button" value="Pay with Mpesa" class="btn" data-bs-toggle="modal" data-bs-target="#mpesa" style="background: #1DA1F2 ">
                              </div>
                           </div>
                     </form>
                  </div>
               </span>
               <div>
            </div>
         </div>
      </div>
   </div>
</div>
{{-- end checkout modal --}}
