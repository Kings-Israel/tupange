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
            <h2>Edit Guest</h2>
            <div>
               <a class="btn btn-info mt-1" style="background-color: #1da1f2; color: white; text-decoration: none;" href="{{ route('client.event.guests', ['event' => $guest->event]) }}">Back to Guest List</a>
            </div>
         </div><!-- .member-place-wrap -->
         <div class="filter-group">
            <div class="row">
                <div class="form-group col-md-12">
                    <form action="{{ route('client.event.guest.edit', $guest) }}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        <input type="hidden" name="guest_id" value="{{ $guest->id }}">
                        <input type="hidden" name="event_id" value="{{ $guest->event->id }}">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="field-group field-input">
                                    <label for="company_logo">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="Default" {{ $guest->status == "Default" ? 'selected' : '' }}>Default</option>
                                        <option value="Invited" {{ $guest->status == "Invited" ? 'selected' : '' }}>Invited</option>
                                        <option value="Confirmed" {{ $guest->status == "Confirmed" ? 'selected' : '' }}>Confirmed</option>
                                        <option value="Confirmed" {{ $guest->status == "Attended" ? 'selected' : '' }}>Attended</option>
                                    </select>
                                    <x-input-error for="status"></x-input-error>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="field-group field-input">
                                    <label>First Name *</label>
                                    <input type="phone_number" name="first_name" class="form-control" value="{{ $guest->first_name }}">
                                    <x-input-error for="first_name"></x-input-error>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="field-group field-input">
                                    <label>Last Name *</label>
                                    <input type="phone_number" class="form-control" name="last_name" value="{{ $guest->last_name }}">
                                    <x-input-error for="last_name"></x-input-error>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="field-group field-input">
                                    <label for="company_logo">Guest Category</label>
                                       <select name="type" id="guest-category-select-type-{{ $guest->id }}" class="form-control" @if(!collect(['General Admission', 'VIP', 'VVIP'])->contains($guest->type)) hidden @endif>
                                          <option value="General Admission" {{ $guest->type == "General Admission" ? 'selected' : '' }}>General Admission</option>
                                          <option value="VIP" {{ $guest->type == "VIP" ? 'selected' : '' }}>VIP</option>
                                          <option value="VVIP" {{ $guest->type == "VVIP" ? 'selected' : '' }}>VVIP</option>
                                       </select>
                                       <input type="text" name="custom_type" class="form-control" id="guest-category-custom-type-{{ $guest->id }}" placeholder="Enter The Guest's Category Type" value="{{ $guest->type }}" @if(collect(['General Admission', 'VIP', 'VVIP'])->contains($guest->type)) hidden @endif>
                                    <x-input-error for="type"></x-input-error>
                                 </div>
                                 <div class="field-group field-input custom_category">
                                    <label for="custom-category-{{ $guest->id }}">
                                       <input type="checkbox" name="custom_category" id="custom-category-{{ $guest->id }}" {{ !collect(['General Admission', 'VIP', 'VVIP'])->contains($guest->type) ? 'checked' : '' }} value="custom_category" onchange="enterCustomGuestCategoryEdit(this)">Enter a custom guest category
                                    </label>
                                 </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="field-group field-input">
                                    <label for="company_logo">Phone</label>
                                    <input type="phone_number" name="phone_number" id="phone_number_{{ $guest->id }}" class="form-control" value="{{ $guest->phone_number }}">
                                    <x-input-error for="phone"></x-input-error>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="field-group field-input">
                                    <label>Email</label>
                                    <input type="phone_number" name="email" class="form-control" id="email_{{ $guest->id }}" value="{{ $guest->email }}">
                                    <x-input-error for="email"></x-input-error>
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
                                       <option value="{{ $ticket->title }}" {{ $guest->ticket_title === $ticket->title ? 'selected' : '' }} data-price="{{ $ticket->price }}">{{ $ticket->title }} ({{ $ticket->price }})</option>
                                    @endforeach
                                 </select>
                                 <x-input-error for="ticket_title"></x-input-error>
                              </div>
                           </div>
                           <input type="hidden" name="ticket_price" id="ticket_price">
                           <div class="col-lg-4">
                              <div class="field-group field-input">
                                 <label>No. of Guests</label>
                                 <input type="phone_number" min="1" name="guests" class="form-control" value="{{ $guest->guests }}" id="guest_number">
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
                        <div class="row">
                           <div class="col-lg-6">
                              <div class="field-group field-input">
                                 <label class="label">Role</label>
                                 <select name="role" class="form-control">
                                    <option value="">Select if the user has a role in the event</option>
                                    <option value="Committee" {{ $guest->role && $guest->role->role == "Committee" ? 'selected' : '' }}>Committee</option>
                                    <option value="Tasks" {{ $guest->role && $guest->role->role == "Tasks" ? 'selected' : '' }}>Tasks</option>
                                 </select>
                                 <x-input-error for="role"></x-input-error>
                              </div>
                           </div>
                            <div class="col-lg-6">
                                <div class="field-group field-input">
                                    <label for="company_logo">Address</label>
                                    <input type="address" name="address" class="form-control" value="{{ $guest->address }}">
                                    <x-input-error for="address"></x-input-error>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="field-group field-input">
                                    <label for="company_logo">Diet Restrictions</label>
                                    <textarea name="diet_instructions" class="form-control" placeholder="Diet restrictions/instructions">{{ $guest->diet_instructions }}</textarea>
                                    <x-input-error for="diet_instructions"></x-input-error>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="field-group field-input">
                                    <label>Table Number</label>
                                    <input type="number" name="table_number" class="form-control" min="1" value="{{ $guest->table_number }}">
                                    <x-input-error for="table_number"></x-input-error>
                                </div>
                            </div>
                        </div>
                        <br>
                        <h4>INVITATION DETAILS</h4>
                        <p><strong>NOTE:</strong>When you do not provide invitation contacts, this guest will not receive an online invitation.</p>
                        <hr>
                        <div class="row">
                           <div class="col-lg-12 mt-2">
                               <div class="field-group field-input">
                                   <label for="company_contacts">
                                       <input type="checkbox" name="use_provided_guest_contacts_{{ $guest->id }}" id="use_provided_guest_contacts_{{ $guest->id }}" value="use_provided_guest_contacts" onchange="useProvidedContacts({{ $guest->id }})">Use provided contact information
                                   </label>
                               </div>
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="field-group field-input">
                                    <label>Invitation Phone Number</label>
                                    <input type="phone_number" name="invitation_phone_number" id="invitation_phone_number_{{ $guest->id }}" class="form-control" placeholder="SMS/Whatsapp Phone Number" value="{{ $guest->invitation_phone_number }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="field-group field-input">
                                    <label>Invitation Email</label>
                                    <input type="email" name="invitation_email" class="form-control" id="invitation_email_{{ $guest->id }}" placeholder="Email Contact" value="{{ $guest->invitation_email }}">
                                    <x-input-error for="invitation_email"></x-input-error>
                                </div>
                            </div>
                        </div>
                        <div class="field-group field-input">
                            <label for="company_logo">Invitation custom message</label>
                            <textarea name="invitation_custom_message" class="form-control" placeholder="Custom Accompanying Message">{{ $guest->invitation_custom_message }}</textarea>
                            <x-input-error for="invitation_custom_message"></x-input-error>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="field-group field-input">
                                    <label>Invitation Custom Question</label>
                                    <input type="phone_number" name="custom_question" class="form-control" placeholder="Custom Question" value="{{ $guest->custom_question }}">
                                    <x-input-error for="custom_question"></x-input-error>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="field-group field-input">
                                    <input type="text" name="custom_answer" class="form-control mt-4" placeholder="Answer" value="{{ $guest->custom_answer }}">
                                    <x-input-error for="custom_answer"></x-input-error>
                                </div>
                            </div>
                        </div>
                        <div class="field-group field-input">
                           <label>Notes</label>
                           <textarea name="notes" class="form-control" placeholder="Notes">{{ $guest->notes }}</textarea>
                           <x-input-error for="notes"></x-input-error>
                        </div>
                        <br>
                        <input type="submit" value="Submit" class="btn" style="float: right;">
                    </form>
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

      $(document).ready(function() {
         calculatePrice()
      })

      function calculatePrice() {
         ticket_price = $('#ticket').find(':selected').data('price')
         $('#ticket_price').val(ticket_price)
         guest_number = $('#guest_number').val()
         let totalAmount = ticket_price * guest_number
         $('#amount').val(totalAmount)
      }
      function enterCustomGuestCategoryEdit(value) {
         if (value.checked) {
            $('#guest-category-select-type-'+{!! json_decode($guest->id) !!}).attr('hidden', 'hidden')
            $('#guest-category-custom-type-'+{!! json_decode($guest->id) !!}).removeAttr('hidden')
         } else {
            $('#guest-category-select-type-'+{!! json_decode($guest->id) !!}).removeAttr('hidden')
            $('#guest-category-custom-type-'+{!! json_decode($guest->id) !!}).attr('hidden', 'hidden')
         }
      }
      //    $('#guest-category-select-type-'+{!! json_decode($guest->id) !!}).on('change', function(event) {
      //      if (event.target.value === 'Custom') {
      //       $('#guest-category-select-type-'+{!! json_decode($guest->id) !!}).attr('hidden', 'hidden')
      //       $('#guest-category-custom-type-'+{!! json_decode($guest->id) !!}).removeAttr('hidden')
      //      }
      //   })

      function useProvidedContacts(id) {
         var result = document.querySelector('input[name="use_provided_guest_contacts_'+id+'"]:checked');
         if(result){
               document.getElementById("invitation_phone_number_"+id).value = document.getElementById("phone_number_"+id).value;
               document.getElementById("invitation_email_"+id).value = document.getElementById('email_'+id).value;
         }
         else{
            document.getElementById("invitation_phone_number_"+id).value = ''
            document.getElementById("invitation_email_"+id).value = ''
         }
      }
    </script>
  @endpush
