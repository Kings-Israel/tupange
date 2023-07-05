<div class="modal fade signup" id="add-guest-{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
               <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close m-2" style="float: right; font-size: 20px">
                  <i class="las la-times la-24-black"></i>
               </a><!-- .popup__close -->
         </span>
         <div class="modal-body">
               <h3 class="modal-header-title">Add Guest</h3>
               <div class="login-form">
                  <div class="submit-section">
                     <div class="row">
                           <div class="form-group col-md-12">
                              <form action="{{ route('client.event.guest.add', $event) }}" method="POST" id="add-guest-form" enctype="multipart/form-data" >
                                 @csrf
                                 <input type="hidden" name="event_id" value="{{ $event->id }}">
                                 <div class="row">
                                       <div class="col-lg-4">
                                          <div class="field-group field-input">
                                             <label for="">Status</label>
                                             <select name="status" class="form-control">
                                                   <option value="Default">Default</option>
                                                   <option value="Invited">Invited</option>
                                                   <option value="Confirmed">Confirmed</option>
                                             </select>
                                             <x-input-error for="title"></x-input-error>
                                          </div>
                                       </div>
                                       <div class="col-lg-4">
                                          <div class="field-group field-input">
                                             <label>First Name *</label>
                                             <input type="text" name="first_name" class="form-control">
                                             <span id="first_name-Error"><strong style="color: red"></strong></span>
                                          </div>
                                       </div>
                                       <div class="col-lg-4">
                                          <div class="field-group field-input">
                                             <label>Last Name *</label>
                                             <input type="text" name="last_name" class="form-control">
                                             <span id="last_name-Error"><strong style="color: red"></strong></span>
                                          </div>
                                       </div>
                                 </div>
                                 <div class="row">
                                       <div class="col-lg-4">
                                          <div class="field-group field-input">
                                             <label for="">Guest Category</label>
                                             <select name="type" class="form-control" id="guest-category-select-type">
                                                   <option value="General Admission">General Admission</option>
                                                   <option value="VIP">VIP</option>
                                                   <option value="VVIP">VVIP</option>
                                                   {{-- <option value="Custom">Enter Custom Category</option> --}}
                                             </select>
                                             <input type="text" name="custom_type" id="guest-category-custom-type" class="form-control" placeholder="Enter The Guest's Category Type" hidden>
                                             <x-input-error for="type"></x-input-error>
                                          </div>
                                          <div class="field-group field-check">
                                             <label for="custom_category">
                                                 <input type="checkbox" name="custom_category" id="custom_category" value="custom_category" onchange="enterCustomGuestCategory(this)">Enter a custom guest category
                                                 <span class="checkmark">
                                                   <i class="la la-check"></i>
                                                 </span>
                                             </label>
                                         </div>
                                       </div>
                                       <div class="col-lg-4">
                                          <div class="field-group field-input">
                                             <label for="">Phone</label>
                                             <input type="phone_number" name="phone_number" id="phone_number" class="form-control" placeholder="e.g 0707000333">
                                             <x-input-error for="phone"></x-input-error>
                                          </div>
                                       </div>
                                       <div class="col-lg-4">
                                          <div class="field-group field-input">
                                             <label>Email *</label>
                                             <input type="email" name="email" id="email" class="form-control">
                                             <span id="email-Error"><strong style="color: red"></strong></span>
                                          </div>
                                       </div>
                                 </div>
                                 <div class="field-group field-input">
                                    <label class="label">Role</label>
                                    <select name="role" class="form-control">
                                       <option value="">Select if the user has a role in the event</option>
                                       <option value="Committee">Committee</option>
                                       <option value="Tasks">Tasks</option>
                                    </select>
                                    <x-input-error for="role"></x-input-error>
                                 </div>
                                 @if ($event->isCorporate)
                                    <div class="row">
                                       <div class="col-lg-6">
                                          <div class="field-group field-input">
                                             <label for="">Company</label>
                                             <input type="text" name="company" class="form-control">
                                             <x-input-error for="company"></x-input-error>
                                          </div>
                                       </div>
                                       <div class="col-lg-6">
                                          <div class="field-group field-input">
                                                <label for="">Address</label>
                                                <input type="address" name="address" class="form-control">
                                                <x-input-error for="address"></x-input-error>
                                          </div>
                                       </div>
                                    </div>
                                 @endif
                                 <div class="row">
                                       <div class="col-lg-8">
                                          <div class="field-group field-input">
                                             <label for="">Diet Restrictions</label>
                                             <textarea name="diet_instructions" class="form-control" placeholder="Diet restrictions/instructions"></textarea>
                                             <x-input-error for="diet_instructions"></x-input-error>
                                          </div>
                                       </div>
                                       <div class="col-lg-4">
                                          <div class="field-group field-input">
                                             <label>Table Number</label>
                                             <input type="number" name="table_number" class="form-control" min="1">
                                             <x-input-error for="table_number"></x-input-error>
                                          </div>
                                       </div>
                                 </div>
                                 <div class="row">
                                       <div class="col-lg-6 mt-2">
                                          <div class="field-group field-input">
                                             <label for="">
                                                   <input type="checkbox" name="extend_invitation" id="extend_invitation" value="extend_invitation" onchange="selected()"> Extend Invitation
                                                   <div class="field-note">Allow guest to bring company?</div>
                                             </label>
                                          </div>
                                       </div>
                                       <div class="col-lg-6">
                                          <div class="field-group field-input" id="invited_guests_number" hidden>
                                             <label class="m-1">Expected Guests</label>
                                             <input type="number" name="invited_guests_number" class="form-control m-1" min="1">
                                             <x-input-error for="invited_guests_number"></x-input-error>
                                          </div>
                                       </div>
                                 </div>
                                 <br>
                                 @if ($event->isCorporate)
                                    <h4>INVITATION DETAILS</h4>
                                    <p><strong>NOTE:</strong>When you do not provide invitation contacts, this guest will not receive an online invitation.</p>
                                    <hr>
                                    <div class="row">
                                       <div class="col-lg-12 mt-2">
                                          <div class="field-group field-input">
                                             <label for="">
                                                   <input type="checkbox" name="use_provided_guest_contacts" id="use_provided_guest_contacts" value="use_provided_guest_contacts" onchange="useProvidedContact()"> Use provided contact information
                                             </label>
                                          </div>
                                       </div>
                                 </div>
                                 <br>
                                    <div class="row">
                                       <div class="col-lg-6">
                                          <div class="field-group field-input">
                                                <label>Invitation Phone Number</label>
                                                <input type="phone_number" name="invitation_phone_number" id="invitation_phone_number" class="form-control" placeholder="SMS/Whatsapp Phone Number" >
                                          </div>
                                       </div>
                                       <div class="col-lg-6">
                                          <div class="field-group field-input">
                                                <label>Invitation Email</label>
                                                <input type="email" name="invitation_email" id="invitation_email" class="form-control">
                                                <x-input-error for="invitation_email"></x-input-error>
                                          </div>
                                       </div>
                                    </div>
                                 @endif
                                 <div class="row">
                                       <div class="col-lg-6">
                                          <div class="field-group field-input">
                                             <label>Invitation Custom Question</label>
                                             <input type="phone_number" name="custom_question" class="form-control" placeholder="Custom Question" >
                                             <x-input-error for="custom_question"></x-input-error>
                                          </div>
                                       </div>
                                       <div class="col-lg-6">
                                          <div class="field-group field-input">
                                             <input type="text" name="custom_answer" class="form-control mt-4" placeholder="Answer">
                                             <x-input-error for="custom_answer"></x-input-error>
                                          </div>
                                       </div>
                                 </div>
                                 <br>
                                 <input type="submit" value="Submit" id="add-guest" class="btn">
                              </form>
                           </div>
                     </div>
                  </div>
               </div>
         </div>
      </div>
   </div>
   @push('scripts')
   <script>
      function enterCustomGuestCategory(value) {
         if (value.checked) {
            $('#guest-category-select-type').attr('hidden', 'hidden')
            $('#guest-category-custom-type').removeAttr('hidden')
         } else {
            $('#guest-category-select-type').removeAttr('hidden')
            $('#guest-category-custom-type').attr('hidden', 'hidden')
         }
      }

      function selected() {
         var result = document.querySelector('input[name="extend_invitation"]:checked');
         if(result){
            document.getElementById("invited_guests_number").removeAttribute('hidden');
         }
         else{
            document.getElementById("invited_guests_number").setAttribute('hidden', true);
         }
      }

      // $('#guest-category-select-type').on('change', function(event) {
      //    if (event.target.value === 'Custom') {
      //    $('#guest-category-select-type').attr('hidden', 'hidden')
      //    $('#guest-category-custom-type').removeAttr('hidden')
      //    }
      // })

      $("#add-guest").on("click", function(e) {
         e.preventDefault();

         // $("#add_program").submit();
         $(this).attr('disabled', 'disabled')
         $(this).val('Please Wait...')

         let formData = $('#add-guest-form').serializeArray()

         $.ajax({
            method: "POST",
            dataType: "json",
            headers: {
               Accept: "application/json"
            },
            url: "{{ route('client.event.guest.add', $event) }}",
            data: formData,
            success: ({ redirectPath }) => window.location.assign(redirectPath),
            error: (response) => {
               $('#add-guest').removeAttr('disabled')
               $('#add-guest').val('Submit')
               if(response.status === 422) {
                  let errors = response.responseJSON;
                  Object.keys(errors).forEach(function (key) {
                     $("#" + key + "-Error").children("strong").text(errors[key][0]);
                  });
               } else {
                  toastr.options =
                     {
                        "closeButton" : true,
                        "progressBar" : true,
                        "positionClass" : "toast-bottom-right"
                     }
                  toastr.error("An error occurred. Please try again");
               }
            }
         })
      });
   </script>
   @endpush
</div>

<!-- End Modal -->
