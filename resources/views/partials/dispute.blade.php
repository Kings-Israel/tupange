<div class="modal fade signup" id="file-dispute-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="edit-budget-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
       <div class="modal-content" id="sign-up">
           <span>
               <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                   <i class="las la-times la-24-black"></i>
               </a><!-- .popup__close -->
           </span>
           <div class="modal-body">
               <h3 class="modal-header-title">Enter Dispute Reason</h3>
               <div class="login-form">
                   <div class="submit-section">
                       <div class="row">
                           <div class="form-group col-md-12">
                              <form action="{{ route('client.order.dispute') }}" method="POST" enctype="multipart/form-data" >
                                 @csrf
                                 <input type="hidden" name="order_id" value="{{ $order->id }}">
                                 <div class="field-group field-input">
                                    <label class="label">Please explain your dispute here</label>
                                    <textarea name="description" class="form-control" required></textarea>
                                    <x-input-error for="description"></x-input-error>
                                 </div>
                                 <br>
                                 <input type="submit" value="Submit" class="btn">
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
