@extends('layouts.master')
@section('title', 'Add Guest')
@section('css')
<style>
   .site-content {
      background-color: #F8F8F8;
   }
   .filter-group {
      background-color: #fff;
      width: 70%;
      margin-left: 15%;
      padding: 15px;
      border-radius: 10px;
      border: 1px solid #eeeeee;
   }
   #custom_category {
      margin: 5px 5px 5px 0;
   }
   @media only screen and (max-width: 768px) {
      .filter-group {
         width: 100%;
         margin-left: 0;
         margin-top: 20px;
      }
   }
</style>
@endsection
@section('content')
<div class="site-content owner-content">
   <div class="container">
      <div class="member-place-wrap">
         <div class="member-wrap-top">
            <h2>Add Guest</h2>
            <div>
               <a class="btn btn-info mt-1" style="background-color: #1da1f2; color: white; text-decoration: none;" href="{{ route('client.event.guests', ['event' => $event]) }}">Back to Guest List</a>
            </div>
         </div><!-- .member-place-wrap -->
         <div class="filter-group">
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
                              <label for="first_name">First Name *</label>
                              <input type="phone_number" name="first_name" class="form-control">
                              <span id="first_name-Error"><strong style="color: red"></strong></span>
                           </div>
                        </div>
                        <div class="col-lg-4">
                           <div class="field-group field-input">
                              <label>Last Name *</label>
                              <input type="phone_number" name="last_name" class="form-control">
                              <span id="last_name-Error"><strong style="color: red"></strong></span>
                           </div>
                        </div>
                     </div>
                     <div class="row mt-1">
                        <div class="col-lg-4">
                           <div class="field-group field-input">
                              <label for="">Guest Category</label>
                              <select name="type" class="form-control" id="guest-category-select-type">
                                    <option value="General Admission">General Admission</option>
                                    <option value="VIP">VIP</option>
                                    <option value="VVIP">VVIP</option>
                              </select>
                              <input type="phone_number" name="custom_type" id="guest-category-custom-type" class="form-control" placeholder="Enter The Guest's Category Type" hidden>
                              <x-input-error for="type"></x-input-error>
                           </div>
                           <div class="field-group field-input custom_category">
                              <label for="custom_category">
                                 <input type="checkbox" name="custom_category" id="custom_category" value="custom_category" onchange="enterCustomGuestCategory(this)">Enter a custom guest category
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
                              <input type="phone_number" name="email" id="email" class="form-control">
                              <span id="email-Error"><strong style="color: red"></strong></span>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-lg-4">
                           <div class="field-group field-input">
                              <label class="label">Ticket *</label>
                              <select name="ticket_title" class="form-control" id="ticket" onchange="calculatePrice()">
                                 <option value="General Admission" data-price="0">General Admission</option>
                                 @foreach ($tickets as $ticket)
                                    <option value="{{ $ticket->title }}" data-price="{{ $ticket->price }}">{{ $ticket->title }} ({{ $ticket->price }})</option>
                                 @endforeach
                              </select>
                              <x-input-error for="ticket_title"></x-input-error>
                           </div>
                        </div>
                        <input type="hidden" name="ticket_price" id="ticket_price">
                        <div class="col-lg-4">
                           <div class="field-group field-input">
                              <label>No. of Guests</label>
                              <input type="phone_number" min="1" name="guests" class="form-control" value="1" id="guest_number">
                              <x-input-error for="guests"></x-input-error>
                           </div>
                        </div>
                        <div class="col-lg-4">
                           <div class="field-group field-input">
                              <label>Total Amount</label>
                              <input type="phone_number" name="amount" value="0" id="amount" readonly>
                              <x-input-error for="amount"></x-input-error>
                           </div>
                        </div>
                     </div>
                     <div class="field-group field-input mt-1">
                        <label class="label">Role</label>
                        <select name="role" class="form-control">
                           <option value="">Select if the user has a role in the event</option>
                           <option value="Committee">Committee</option>
                           <option value="Tasks">Tasks</option>
                        </select>
                        <x-input-error for="role"></x-input-error>
                     </div>
                     @if ($event->isCorporate)
                        <div class="row mt-1">
                           <div class="col-lg-6">
                              <div class="field-group field-input">
                                 <label for="">Company</label>
                                 <input type="phone_number" name="company" class="form-control">
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
                     <div class="row mt-1">
                        <div class="col-lg-8">
                           <div class="field-group field-input">
                              <label for="">Diet Restrictions</label>
                              <textarea name="diet_instructions" class="form-control" placeholder="Diet restrictions/instructions"></textarea>
                              <x-input-error for="diet_instructions"></x-input-error>
                           </div>
                        </div>
                        <div class="col-lg-4">
                           <div class="field-group field-input">
                              <label for="">Table Number</label>
                              <input type="phone_number" name="table_number" class="form-control" min="1" placeholder="Table Number">
                              <x-input-error for="table_number"></x-input-error>
                           </div>
                        </div>
                     </div>
                     <div class="row mt-1">
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
                        <div class="row mt-1">
                           <div class="col-lg-12 mt-2">
                              <div class="field-group field-input">
                                 <label for="">
                                       <input type="checkbox" name="use_provided_guest_contacts" id="use_provided_guest_contacts" value="use_provided_guest_contacts" onchange="useProvidedContact(this)"> Use provided contact information
                                 </label>
                              </div>
                           </div>
                        </div>
                        <br>
                        <div class="row mt-1">
                           <div class="col-lg-6">
                              <div class="field-group field-input">
                                    <label>Invitation Phone Number</label>
                                    <input type="phone_number" name="invitation_phone_number" id="invitation_phone_number" class="form-control" placeholder="SMS/Whatsapp Phone Number" >
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="field-group field-input">
                                    <label>Invitation Email</label>
                                    <input type="phone_number" name="invitation_email" id="invitation_email" class="form-control">
                                    <x-input-error for="invitation_email"></x-input-error>
                              </div>
                           </div>
                        </div>
                     @endif
                     <div class="row mt-1">
                        <div class="col-lg-6">
                           <div class="field-group field-input">
                              <label>Invitation Custom Question</label>
                              <input type="phone_number" name="custom_question" class="form-control" placeholder="Custom Question" >
                              <x-input-error for="custom_question"></x-input-error>
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="field-group field-input">
                              <input type="phone_number" name="custom_answer" class="form-control mt-4" placeholder="Answer">
                              <x-input-error for="custom_answer"></x-input-error>
                           </div>
                        </div>
                     </div>
                     <div class="field-group field-input">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" placeholder="Notes"></textarea>
                        <x-input-error for="notes"></x-input-error>
                     </div>
                     <br>
                     <input type="submit" value="Submit" id="add-guest" class="btn" style="float: right">
                  </form>
               </div>
            </div>
         </div>
         </div>
      </div>
   </div>
</div>
@endsection
@push('scripts')
<script>
   $('#guest_number').on('input', function() {
      calculatePrice()
   })

   function useProvidedContact(value) {
      if (value.checked) {
         $('#invitation_phone_number').val($('#phone_number').val())
         $('#invitation_email').val($('#email').val())
      } else {
         $('#invitation_phone_number').val('')
         $('#invitation_email').val('')
      }
   }

   function calculatePrice() {
      ticket_price = $('#ticket').find(':selected').data('price')
      $('#ticket_price').val(ticket_price)
      guest_number = $('#guest_number').val()
      let totalAmount = ticket_price * guest_number
      $('#amount').val(totalAmount)
   }

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
