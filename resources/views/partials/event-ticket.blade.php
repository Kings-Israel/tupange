<div class="modal fade signup" id="get-ticket-{{ $guest->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
            <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close m-2" style="float: right; font-size: 20px">
                  <i class="las la-times la-24-black"></i>
            </a><!-- .popup__close -->
         </span>
         <div class="modal-body">
            <h1 class="modal-header-title">TICKET</h1>
            <h2>{{ Str::upper($guest->ticket_title) }}</h2>

            <div class="login-form">
               <div class="submit-section">
                  <div class="row">
                     <div class="form-group col-md-12">
                        <div class="row">
                           <div class="col-lg-7">
                              To: {{ Str::upper($guest->first_name) }} {{ Str::upper($guest->last_name) }}
                              <br>
                              {{ $guest->barcode }} (admit {{ $guest->guests }})
                              <hr>
                              DETAILS
                              <br>
                              <h4>{{ $guest->event->event_name }}</h4>
                              <br>
                              Event Starts: <b>{{ $guest->event->event_start_date }}</b>
                              <br>
                              Event Ends: <b>{{ $guest->event->event_end_date }}</b>
                              <br>
                              Location: <b>{{ $guest->event->event_location }}</b>
                              <br>
                              @if ($guest->event->custom_message != null)
                                 Other Details: <b>{{ $guest->event->custom_message }}</b>
                              @endif
                           </div>
                           <div class="col-lg-5">
                              {!! DNS2D::getBarcodeHTML(config('services.app_url.url').'/event/ticket/'.(string) $guest->barcode, 'QRCODE') !!}
                           </div>
                        </div>
                        <br>
                        <div class="row">
                           <div class="col-7">
                              Ticket Price: <b>Ksh.{{ number_format((double) $guest->ticket_price * (int) $guest->guests) }}</b>
                           </div>
                           <div class="col-5">
                              @if ($guest->is_paid)
                                 <span class="btn">Paid</span>
                              @else
                                 <span class="btn" style="background-color: red">Not Paid</span>
                              @endif
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer" style="background: #000">
            <div style="text-align: center">
               <h4>Powered By</h4><img src="{{ asset('assets/images/assets/logo-1.png') }}" style="width: 80px">
            </div>
         </div>
         <div class="row m-2">
            <div class="col-lg-2">
               <a href="{{ route('client.event.ticket.send', $guest) }}" class="btn">
                  Send
               </a>
            </div>
            <div class="col-lg-5">
               <a href="{{ route('client.event.ticket.map.send', $guest) }}" class="btn" style="background-color: #F58C1C">
                  Send With Event Location Map Link
               </a>
            </div>
            <div class="col-lg-3">
               <a href="{{ route('client.event.ticket.download', $guest) }}" class="btn" style="background-color: black">
                  Print
               </a>
            </div>
         </div>
       </div>
   </div>
</div>

<!-- End Modal -->
