<div class="modal fade signup" id="client-delete-order-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
       <div class="modal-content" id="sign-up">
           <span>
               <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close m-2" style="float: right; font-size: 20px">
                   <i class="las la-times la-24-black"></i>
               </a><!-- .popup__close -->
           </span>
           <div class="modal-body">
               <h3 class="modal-header-title" style="color: red">Delete Order</h3>
               <div class="login-form">
                   <div class="submit-section">
                       <div class="row">
                           <div class="form-group col-md-12">
                                   <input type="hidden" name="order_id" value="{{ $order->id }}">
                                   <p style="color: red">
                                       Are you sure you want to delete <strong>Order {{ $order->order_id }} ({{ $order->service->service_title }})</strong>.
                                       <br>
                                       This action cannot be undone.
                                   </p>
                                   <br>
                                   <a href="{{ route('client.order.delete', $order) }}" class="btn btn-danger" style="background: red">Delete Order</a>
                                   {{-- <input type="submit" value="Delete Order" class="btn btn-danger" style="background: red"> --}}
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>
</div>

<!-- End Modal -->
