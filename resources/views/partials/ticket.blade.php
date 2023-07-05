@extends('layouts.master')
@section('title', 'Ticket')
@section('css')
<style>
   .ticket-container {
      max-width: 60%;
      background-color: #F8F8F8;
   }
   .ticket-details {
      background-color: #fff;
      border-radius: 5px;
      margin: 10px 0 10px 0;
      padding: 10px 10px 0 10px;
   }
   .ticket-header {
      margin: 50px;
   }

   .ticket-header-image {
      margin-top: -100px;
      float: right;
   }

   .ticket-header-image img {
      width: 220px;
      height: 200px;
      object-fit: contain;
   }

   .event_details {
   margin: 50px;
   }

   .event_details .side-2 {
   margin-top: -150px;
   margin-right: 20px;
   float: right;
   }

   .guest-name-section .guest-name {
   font-family: 'Jost', sans-serif;
   font-weight: 700;
   font-size: 18px;
   }
   .guest-barcode {
   margin-bottom: 10px;
   }
   .event-details-header-title {
      margin-top: 10px;
      font-family: 'Jost', sans-serif;
      font-weight: 700;
      font-size: 14px;
   }
   .event-location-link {
      width: 21%;
      background-color: #648E1B;
      padding: 5px;
      padding-left: 8px;
      border-radius: 5px;
   }
   .event-location-link:hover {
      background-color: #a1c760
   }
   .event-location-link a {
      text-decoration: none;
      color: #ffffff;
      justify-content: center;
   }
   .event-start-time, .event-end-time, .event-location {
      font-family: 'Jost', sans-serif;
      font-weight: 700;
      font-size: 18px;
   }
   @media only screen and (max-width: 768px) {
      .ticket-container {
         margin-top: 100px;
         max-width: 100%;
         background-color: #F8F8F8;
      }
   }
</style>
@endsection
@section('content')
<div class="container ticket-container">
   <div class="ticket-details">
      <h1>{{ Str::upper($guest->ticket_title) }}</h1>
      <div class="login-form">
         <div class="submit-section">
            <div class="row">
               <div class="col-lg-8 col-md-6 col-sm-12">
                  <div class="guest-name-section">
                     To:
                     <span class="guest-name">{{ Str::upper($guest->names != '' ? $guest->names : $guest->first_name.' '.$guest->last_name) }}</span>
                  </div>
                  <br>
                  <div class="guest-barcode">
                     {{ $guest->barcode }} @if ($guest->guests != '') (admit {{ $guest->guests }}) @endif
                  </div>
                  <hr>
                  <div class="event-details">
                     <div class="event-details-header">
                        <span class="event-details-header-title">
                           Event Details
                        </span>
                     </div>
                     <br>
                     <div class="event-name">
                        <h4>{{ $guest->event->event_name }}</h4>
                     </div>
                     <br>
                     <div class="event-times">
                        <div class="event-start-time-details">
                           Event Starts:
                           <span class="event-start-time">{{ Carbon\Carbon::parse($guest->event->event_start_date)->format('d M Y') }}</span>
                           at
                           <span class="event-start-time">{{ Carbon\Carbon::parse($guest->event->event_start_date)->format('H:i:s a') }}</span>
                        </div>
                        <br>
                        <div class="event-start-time-details">
                           Event Ends:
                           <span class="event-end-time">
                              {{ Carbon\Carbon::parse($guest->event->event_end_date)->format('d M Y') }}
                           </span>
                           at
                           <span class="event-end-time">
                              {{ Carbon\Carbon::parse($guest->event->event_end_date)->format('H:i:s a') }}
                           </span>
                        </div>
                     </div>
                     <br>
                     <div class="event-location-section">
                        Location:
                        <span class="event-location">{{ $guest->event->event_location }}</span>
                     </div>
                     <br>
                     @if ($guest->event->custom_message != null)
                        <div class="event-meta-details-section">
                           Other Details:
                           <span class="event-meta-details">{{ $guest->event->custom_message }}</span>
                        </div>
                     @endif
                     <div class="barcode-map">
                        <a target="_blank" href="https://www.google.com/maps/search/?api=1&query={{ $guest->event->event_location_lat }},{{ $guest->event->event_location_long }}" class="badge">Open Map</a>
                     </div>
                  </div>
               </div>
               <div class="col-lg-4 col-md-6 col-sm-12">
                  @auth
                     {{-- If is event organizer, show the payment status and guests atteneded status of the ticket --}}
                     @if (auth()->user()->id === $guest->event->user->id)
                        <div class="d-flex justify-content-between">
                           {{-- Show Payment Status of ticket --}}
                           <div>
                              <p>Payment Status:</p>
                              @if ($guest->is_paid)
                                 <span class="badge">Paid</span>
                              @else
                                 <span class="badge" style="background-color:red">Not Paid</span>
                              @endif
                           </div>
                           {{-- Show Guests Attended Using Ticket --}}
                           <div>
                              <p>Guests Arrived:</p>
                              <span class="badge">{{ $guest->guests_attended }}</span>
                           </div>
                        </div>
                        <br>
                        @if ($guest->guests_attended != $guest->guests)
                           {{-- Update Attended Guests for ticket --}}
                           <form action="{{ route('event.guests.attended', $guest->id) }}" method="POST">
                              @csrf
                              <div class="field-group field-input">
                                 <label for="Guests Attended">Guests Attended</label>
                                 <input type="phone_number" class="form-control" name="guests_attended" value="{{ old('guests_attended') }}">
                                 <x-input-error for="guests_attended" />
                              </div>
                              <input type="submit" value="Submit" class="btn btn-theme" style="float:right; margin-top:5px;">
                           </form>
                        @endif
                     @else
                        {{-- Show payment form if not paid for --}}
                        @if (!$guest->is_paid)
                           <h4>Enter Your Information to make ticket payment</h4>
                           <form action="{{ route('ticket.payment', $guest->id) }}" method="POST" id="ticket-payment-form">
                              @csrf
                              <input type="hidden" name="total_price" value="{{ $guest->ticket_price }}">
                              <input type="hidden" name="ticket_id" value="{{ $guest->id }}">
                              <div class="field-group field-input">
                                 <label for="">Enter Your First Name</label>
                                 <input type="phone_number" name="first_name" class="form-control" id="first_name" value="{{ auth()->user()->first_name }}" required>
                                 <x-input-error for="first_name" />
                              </div>
                              <div class="field-group field-input">
                                 <label for="">Enter Your Last Name</label>
                                 <input type="phone_number" name="last_name" class="form-control" id="last_name" value="{{ auth()->user()->last_name }}" required>
                                 <x-input-error for="last_name" />
                              </div>
                              <div class="field-group field-input">
                                 <label for="">Enter Email</label>
                                 <input type="phone_number" name="email" id="email" class="form-control" value="{{ auth()->user()->email }}" required>
                                 <x-input-error for="email" />
                              </div>
                              <div class="field-group field-input">
                                 <label for="">Enter Phone Number</label>
                                 <input type="phone_number" name="phone_number" id="phone-number" class="form-control" value="{{ auth()->user()->phone_number }}" required>
                                 <x-input-error for="phone_number" />
                              </div>
                              <div class="field-group field-input">
                                 <input type="submit" value="Pay" class="btn btn-theme" id="ticket-payment-submit" style="float:right; margin-top:5px;">
                              </div>
                           </form>
                        @endif
                     @endif
                  @else
                     {{-- Show Payment form for guest user --}}
                     @if (!$guest->is_paid)
                        <h4>Enter Your Information to make ticket payment</h4>
                        <form action="{{ route('ticket.payment', $guest->id) }}" method="POST" id="ticket-payment-form">
                           @csrf
                           <input type="hidden" name="total_price" value="{{ $guest->ticket_price }}">
                           <input type="hidden" name="ticket_id" value="{{ $guest->id }}">
                           <div class="field-group field-input">
                              <label for="">Enter Your First Name</label>
                              <input type="phone_number" name="first_name" class="form-control" id="first_name" value="{{ old('first_name') }}" required>
                              <x-input-error for="first_name" />
                           </div>
                           <div class="field-group field-input">
                              <label for="">Enter Your Last Name</label>
                              <input type="phone_number" name="last_name" class="form-control" id="last_name" value="{{ old('last_name') }}" required>
                              <x-input-error for="last_name" />
                           </div>
                           <div class="field-group field-input">
                              <label for="">Enter Email</label>
                              <input type="phone_number" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                              <x-input-error for="email" />
                           </div>
                           <div class="field-group field-input">
                              <label for="">Enter Phone Number</label>
                              <input type="phone_number" name="phone_number" id="phone-number" class="form-control" value="{{ old('phone_number') }}" required>
                              <x-input-error for="phone_number" />
                           </div>
                           <div class="field-group field-input">
                              <input type="submit" value="Pay" class="btn btn-theme" id="ticket-payment-submit" style="float:right; margin-top:5px;">
                           </div>
                        </form>
                     @else
                        <span class="badge">Paid</span>
                     @endif
                  @endauth
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@push('scripts')
<script>
   // $('#ticket-payment-submit').on('click', function(e) {
   //    e.preventDefault()

   //    var phone_number = $('#phone-number').val()
   //    var name = $('#name').val()
   //    var email = $('#email').val()
   //    if (phone_number === '' || name === '' || email === '') {
   //       toastr.options =
   //       {
   //          "closeButton" : true,
   //          "progressBar" : true,
   //          "positionClass" : "toast-bottom-right"
   //       }
   //       toastr.error("Please enter name, email and phone number");
   //       return
   //    }
   //    var payment_method = $('#payment-method').val()

   //    if (payment_method == 'Mpesa') {
   //       $('#ticket-payment-form-').submit()
   //    } else if(payment_method == 'Debit Card') {
   //       $('#ticket-payment-form').attr('action', 'https://portal.host.iveri.com/Lite/Authorise.aspx')
   //       $('#ticket-payment-form').submit()
   //    }
   // })
</script>
@endpush
