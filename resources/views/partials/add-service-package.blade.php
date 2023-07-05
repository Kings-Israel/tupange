<div class="modal fade signup" id="add-service-package" tabindex="-1" role="dialog" aria-labelledby="add-service-package-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
       <div class="modal-content">
           <span>
               <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close m-2" style="float: right; font-size: 20px">
                   <i class="las la-times la-24-black"></i>
               </a><!-- .popup__close -->
           </span>
           <div class="modal-body">
               <h3>Add Pricing and Package</h3>
               <form method="POST" action="{{ route('vendor.service.pricing.add') }}" class="form-log form-content" id="add-service-package-form">
                     @csrf
                     <input type="hidden" name="service_id" value="{{ $service->id }}">
                     <div class="field-inline">
                        <div class="row">
                           <div class="col-6">
                              <div class="field-input">
                                 <input type="text" placeholder="Title" value="{{ old('service_pricing_title') }}" class="form-control" name="service_pricing_title" required>
                                 <span id="service_pricing_titleError">
                                    <strong class="error-message"></strong>
                                 </span>
                              </div>
                           </div>
                           <div class="col-6">
                              <div class="field-input">
                                 <input type="text" placeholder="Price (Ksh.)" min="1" class="form-control" name="service_pricing_price" id="service_pricing_price" required>
                                 <span id="service_pricing_priceError">
                                    <strong class="error-message"></strong>
                                 </span>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="field-group">
                        <h4 class="label">Description</h4>
                        <textarea name="service_pricing_description" class="form-control" style="height: 80px" required>{{ old('service_pricing_description') }}</textarea>
                        <span id="service_pricing_descriptionError">
                           <strong class="error-message"></strong>
                        </span>
                     </div>
                     <br>
                     {{-- <div class="field-group">
                        <h4 class="label">Pricing Package</h4>
                        <p>Enter Package Deals for this item</p>
                        <div id="pricing-package-items">
                           <div class="row pricing-package-item mb-1">
                                 <div class="col-11">
                                    <textarea name="service_packages[]" class="form-control" style="height: 50px"></textarea>
                                 </div>
                                 <div class="col-1" id="pricing-package-delete" style="cursor: pointer">
                                    <i class="fas fa-trash"></i>
                                 </div>
                           </div>
                        </div>
                     </div>
                     <span class="btn-sm" id="add-pricing-package-button" style="cursor: pointer">
                        <i class="fas fa-plus"></i>
                        Add Package
                     </span> --}}
                     <hr>
                     <input type="submit" value="Submit" class="btn"  id="add-pricing">
               </form>
           </div>
       </div>
   </div>
   @push('scripts')
      <script>
          $('#service_pricing_price').on('input', function() {
            var num = $(this).val().replace(/,/gi, "");
            var num2 = num.split(/(?=(?:\d{3})+$)/).join(",")
            $(this).val(num2)
         })

         $('#add-service-package-form').on('submit', function(e) {
            e.preventDefault()
            $("#add-pricing").val('Please Wait...')
            $("#add-pricing").attr('disabled', 'disabled')
            let formData = $(this).serializeArray()
            $.ajax({
               method: "POST",
               dataType: "json",
               headers: {
                  Accept: "application/json",
                  'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
               },
               url: "{{ route('vendor.service.pricing.add') }}",
               data: formData,
               success: ({ redirectPath }) => {
                  window.location.assign(redirectPath)
               },
               error: (response) => {
                  if(response.status === 422) {
                     let errors = response.responseJSON.errors;
                     Object.keys(errors).forEach(function (key) {
                        $("#" + key + "Error").children("strong").text(errors[key][0]);
                     });
                     toastr.options =
                           {
                              "closeButton" : true,
                              "progressBar" : true,
                              "positionClass" : "toast-bottom-right"
                           }
                     toastr.error("Please check the data and try again");
                     $("#add-pricing").val('Submit')
                     $("#add-pricing").removeAttr('disabled')
                  } else if (response.status === 400) {
                     if (response.responseJSON.errors == "Invalid Service Price Value") {
                        $("#service_pricing_priceError").children("strong").text(response.responseJSON.errors);
                     }
                     toastr.options =
                           {
                              "closeButton" : true,
                              "progressBar" : true,
                              "positionClass" : "toast-bottom-right"
                           }
                     toastr.error("Please check the data and try again");
                     $("#add-pricing").val('Submit')
                     $("#add-pricing").removeAttr('disabled')
                  } else {
                     $("#add-pricing").val('Submit')
                     $("#add-pricing").removeAttr('disabled')
                     toastr.options =
                           {
                              "closeButton" : true,
                              "progressBar" : true,
                              "positionClass" : "toast-bottom-right"
                           }
                     toastr.error("An error occured. Please try again");
                  }
               }
            })
         })
      </script>
   @endpush
</div>

<!-- End Modal -->

