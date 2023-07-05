<div class="modal fade signup" id="add-custom-quote" tabindex="-1" role="dialog" aria-labelledby="add-custom-quote" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
        <div class="modal-content" id="sign-up">
            <span>
                <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close m-2" style="float: right; font-size: 20px">
                    <i class="las la-times la-24-black"></i>
                </a><!-- .popup__close -->
            </span>
            <div class="modal-body">
                <h3 class="modal-header-title">Add Custom Quote</h3>
                <div class="login-form">
                    <div class="submit-section">
                        <div class="row">
                            <div class="form-group col-md-12">
                                <form action="{{ route('vendor.order.custom.quote') }}" method="POST" class="add-custom-quote-form" enctype="multipart/form-data" >
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $order->id }}" id="">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="field-group field-input">
                                                <label for="company_logo">Title</label>
                                                <input type="text" name="order_pricing_title" class="form-control" required>
                                                <x-input-error for="title"></x-input-error>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="field-group field-input">
                                                <label for="company_logo">Price</label>
                                                <input type="number" name="order_pricing_price" class="form-control" min="1" required>
                                                <x-input-error for="value"></x-input-error>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field-group field-input">
                                        <label for="order_agreement">Order Agreement</label>
                                        <textarea name="order_pricing_agreement" id="order_pricing_agreement" class="form-control" placeholder="Enter your terms and conditions agreement for this quotation. e.g. I will deliver this service at 12PM for 5 hours..."></textarea>
                                    </div>
                                    <br>
                                    <input type="submit" value="Submit" class="btn submit-custom-quote">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
   <script>
      function checkIfEmailInString(text) {
         var re = /(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/;
         return re.test(text.replace(/ /g,''));
      }

      function checkIfPhoneNumberInString(text) {
         var phoneExp = /(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?/img;
         return phoneExp.test(text.replace(/ /g,''))
      }

      $('.submit-custom-quote').on('click', function (e) {
         e.preventDefault();
         let pricing_agreement = $('#order_pricing_agreement').val()
         if (pricing_agreement) {
            if (checkIfEmailInString(pricing_agreement) || checkIfPhoneNumberInString(pricing_agreement)) {
               toastr.options =
                  {
                     "closeButton" : true,
                     "progressBar" : true,
                     "positionClass" : "toast-bottom-right"
                  }
               toastr.error("You cannot share phone numbers or email addresses!!");

               return
            } else {
               $('.add-custom-quote-form').submit()
            }
         } else {
            $('.add-custom-quote-form').submit()
         }
      })
   </script>
@endpush

<!-- End Modal -->
