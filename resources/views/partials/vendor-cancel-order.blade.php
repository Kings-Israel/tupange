<div class="modal fade signup" id="cancel-order-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
            <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                  <i class="las la-times la-24-black"></i>
            </a><!-- .popup__close -->
         </span>
         <div class="modal-body">
            <h3 class="modal-header-title">Cance Order {{ $order->order_id }}</h3>
            <div class="login-form">
               <div class="submit-section">
                  <div class="row">
                     <div class="form-group col-md-12">
                        @if ((3 - $cancelCount) > 0)
                           <p style="color: red">Are you sure you want to cancel the order {{ $order->order_id }}. You have cancelled {{ $cancelCount }} orders in the past 3 months. You have {{ 3 - $cancelCount }} attempts left or your account will be suspended.</p>
                        @elseif((3 - $cancelCount) <= 0)
                           <p style="color: red">Are you sure you want to cancel the order {{ $order->order_id }}. You have 0 remaining attempts. This actions will suspend your vendor account as you have cancelled more than 3 orders in the past 3 months.</p>
                        @endif
                        <form action="{{ route('vendor.order.cancel') }}" method="post">
                           @csrf
                           <input type="hidden" name="order_id" value="{{ $order->id }}">
                           <button class="btn" type="submit" style="background: red">Cancel Order</button>
                           <span data-bs-dismiss="modal" class="popup__close">Cancel</span>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- End Modal -->
