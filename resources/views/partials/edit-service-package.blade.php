{{-- Edit Package Modal --}}
<div class="modal fade signup" wire:ignore.self id="pricing-package-update-{{ $pricing->id }}" tabindex="-1" role="dialog" aria-labelledby="pricing-package-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
        <div class="modal-content" id="">
            <div class="modal-body">
                <span>
                    <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                        <i class="las la-times la-24-black"></i>
                    </a><!-- .popup__close -->
                    <h3 class="modal-header-title">Edit Pricing</h3>
                </span>
                <div class="popup-content">
                    <div class="row">
                        <div class="form-group col-lg-12 col-md-12">
                            <form method="POST" action="{{ route('vendor.service.pricing.update') }}" class="form-log form-content" id="edit-service-package-{{ $pricing->id }}">
                                @csrf
                                <input type="hidden" name="pricing_id" value="{{ $pricing->id }}">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="field-group field-input">
                                                <label for="pricing_title">Title</label>
                                                <input type="text" placeholder="Title" class="form-control" value="{{ $pricing->service_pricing_title }}" name="service_pricing_title" required>
                                                <span id="service_pricing_titleError">
                                                   <strong class="error-message"></strong>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="field-group field-input">
                                                <label for="pricing_price">Price</label>
                                                <input type="text" class="form-control" placeholder="Price (Ksh.)" min="1" value="{{ $pricing->service_pricing_price }}" name="service_pricing_price" id="service_pricing_price_{{ $pricing->id }}" required>
                                                <span id="service_pricing_priceError">
                                                   <strong class="error-message"></strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <br>
                                <div class="field-group field-input">
                                    <h4 class="label">Description</h4>
                                    <textarea name="service_pricing_description" class="form-control" style="height: 80px" required>{{ $pricing->service_pricing_description }}</textarea>
                                    <span id="service_pricing_descriptionError">
                                       <strong class="error-message"></strong>
                                    </span>
                                </div>
                                <br>
                                {{-- <div class="field-group">
                                    <h4 class="label">Pricing Package</h4>
                                    <p>Enter Package Deals for this item</p>
                                    <div id="edit-pricing-package-items-{{ $pricing->id }}">
                                        @foreach ($pricing->service_packages as $index => $package)
                                        <div class="row edit-pricing-package-item-{{ $pricing->id }} mb-1">
                                            <div class="col-11">
                                                <textarea name="service_packages_edit[{{ $index }}]" class="form-control" style="height: 50px">{{ $package }}</textarea>
                                            </div>
                                            <div class="col-1" id="edit-pricing-package-delete-{{ $pricing->id }}" style="cursor: pointer">
                                                <i class="fas fa-trash"></i>
                                            </div>
                                        </div>
                                        @endforeach
                                        <div class="row edit-pricing-package-item-{{ $pricing->id }} mb-1">
                                            <div class="col-11">
                                                <textarea name="service_packages_edit[]" class="form-control" style="height: 50px"></textarea>
                                            </div>
                                            <div class="col-1" id="edit-pricing-package-delete-{{ $pricing->id }}" style="cursor: pointer">
                                                <i class="fas fa-trash"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="btn-sm" id="edit-add-pricing-package-button" onclick="addPackage({{ $pricing->id }})" style="cursor: pointer">
                                    <i class="fas fa-plus"></i>
                                    Add Package
                                </span> --}}
                                <hr>
                                <input type="submit" name="submit" value="Update" class="btn" id="edit-pricing-{{ $pricing->id }}" style="float:right">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script>
        $(document.body).on('click', `#edit-pricing-package-delete-`+{!! json_encode($pricing->toArray()) !!}.id, function() {
            var edit_pricing_package_items = $(`#edit-pricing-package-items-`+{!! json_encode($pricing->toArray()) !!}.id)
            let $btn = $(this);
            let $item = $btn.parent();
            if (edit_pricing_package_items.children().length > 1) $item.remove();
        })

        function addPackage(id) {
            var edit_pricing_package_items = $(`#edit-pricing-package-items-${id}`)

            var pricing_package_html = `<div class="row edit-pricing-package-item-${id} mb-1">`
                pricing_package_html += '<div class="col-11"><textarea name="service_packages_edit[]" class="form-control" style="height: 50px"></textarea></div>'
                pricing_package_html += `<div class="col-1" id="edit-pricing-package-delete-${id}" style="cursor: pointer"><i class="fas fa-trash"></i></div>`
                pricing_package_html += '</div>'

            $(pricing_package_html).appendTo(edit_pricing_package_items)
        }

         $(`#service_pricing_price_`+{!! json_encode($pricing->toArray()) !!}.id).on('input', function() {
            var num = $(this).val().replace(/,/gi, "");
            var num2 = num.split(/(?=(?:\d{3})+$)/).join(",")
            $(this).val(num2)
         })

         $(`#edit-service-package-`+{!! json_encode($pricing->toArray()) !!}.id).on('submit', function(e) {
            e.preventDefault()
            $(`edit-pricing-`+{!! json_encode($pricing->toArray()) !!}.id).val('Please Wait...')
            $(`edit-pricing-`+{!! json_encode($pricing->toArray()) !!}.id).attr('disabled', 'disabled')
            let formData = $(this).serializeArray()
            $.ajax({
               method: "POST",
               dataType: "json",
               headers: {
                  Accept: "application/json",
                  'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
               },
               url: "{{ route('vendor.service.pricing.update') }}",
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
                     $(`edit-pricing-`+{!! json_encode($pricing->toArray()) !!}.id).val('Submit')
                     $(`edit-pricing-`+{!! json_encode($pricing->toArray()) !!}.id).removeAttr('disabled')
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
                     $(`edit-pricing-`+{!! json_encode($pricing->toArray()) !!}.id).val('Submit')
                     $(`edit-pricing-`+{!! json_encode($pricing->toArray()) !!}.id).removeAttr('disabled')
                  } else {
                     $(`edit-pricing-`+{!! json_encode($pricing->toArray()) !!}.id).val('Submit')
                     $(`edit-pricing-`+{!! json_encode($pricing->toArray()) !!}.id).removeAttr('disabled')
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
{{-- end edit package modal --}}
