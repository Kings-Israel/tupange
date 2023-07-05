<div class="modal fade signup" id="register-guest-{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
            <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close m-2" style="float: right; font-size: 20px">
                  <i class="las la-times la-24-black"></i>
            </a><!-- .popup__close -->
         </span>
         <div class="modal-body">
            <h3 class="modal-header-title">Register Guest</h3>
            <div class="login-form">
                  <div class="submit-section">
                     <div class="row">
                        <div class="form-group col-md-12">
                              <form action="{{ route('client.event.guest.register') }}" method="POST" enctype="multipart/form-data" >
                                 @csrf
                                 <input type="hidden" name="event_id" value="{{ $event->id }}" id="">
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
                                             <input type="number" min="1" name="guests" class="form-control" value="1" id="guest_number">
                                             <x-input-error for="guests"></x-input-error>
                                          </div>
                                    </div>
                                    <div class="col-lg-4">
                                          <div class="field-group field-input">
                                             <label>Total Amount</label>
                                             <input type="number" name="amount" value="0" id="amount" readonly>
                                             <x-input-error for="amount"></x-input-error>
                                          </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-lg-6">
                                          <div class="field-group field-input">
                                             <label>Guest's Name *</label>
                                             <input type="text" name="names" id="" class="form-control" placeholder="Full Name" required>
                                             <x-input-error for="names"></x-input-error>
                                          </div>
                                    </div>
                                    <div class="col-lg-6">
                                          <div class="field-group field-input">
                                             <label>Phone Number</label>
                                             <input type="phone_number" name="phone_number" class="form-control" placeholder="Phone Number">
                                             <x-input-error for="phone_number"></x-input-error>
                                          </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-lg-6">
                                       <div class="field-group field-input">
                                          <label>Email</label>
                                          <input type="email" name="email" class="form-control" id="" placeholder="Email Address">
                                          <x-input-error for="email"></x-input-error>
                                       </div>
                                    </div>
                                    <div class="col-lg-6">
                                       <div class="field-group field-input">
                                          <label>Company</label>
                                          <input type="text" name="company" class="form-control" id="" placeholder="Company/Organization">
                                          <x-input-error for="company"></x-input-error>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-lg-6">
                                          <div class="field-group field-input">
                                             <label>Address</label>
                                             <input type="address" name="address" class="form-control">
                                             <x-input-error for="address"></x-input-error>
                                          </div>
                                    </div>
                                    <div class="col-lg-6">
                                       <div class="field-group field-input">
                                          <label>Notes</label>
                                          <textarea name="notes" class="form-control" placeholder="Notes"></textarea>
                                          <x-input-error for="notes"></x-input-error>
                                       </div>
                                    </div>
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
   @push('scripts')
   <script>
      $('#guest_number').on('input', function() {
         calculatePrice()
      })

      function calculatePrice() {
         ticket_price = $('#ticket').find(':selected').data('price')
         $('#ticket_price').val(ticket_price)
         guest_number = $('#guest_number').val()
         let totalAmount = ticket_price * guest_number
         $('#amount').val(totalAmount)
      }
   </script>
   @endpush
</div>

<!-- End Modal -->
