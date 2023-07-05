<div class="modal fade signup" id="customer-review" tabindex="-1" role="dialog" aria-labelledby="customer-review" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
            <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                  <i class="las la-times la-24-black"></i>
            </a><!-- .popup__close -->
         </span>
         <div class="modal-header">
            <h3 class="modal-header-title">Leave a review</h3>
         </div>

         <div class="modal-body">
            <div class="login-form">
                <div class="submit-section">
                    <div class="row">
                        <div class="form-group col-md-12">
                           <form action="{{ route('customer-review') }}" method="POST" enctype="multipart/form-data" >
                              @csrf
                              <div class="field-group field-input">
                                 <label class="label">Please let us know about your experience in the website.</label>
                                 <textarea name="customer_review" class="form-control">{{ old('customer_review') }}</textarea>
                                 <x-input-error for="customer_review"></x-input-error>
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
