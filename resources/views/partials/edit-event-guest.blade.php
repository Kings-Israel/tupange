<div class="modal fade signup" id="edit-event-guest-{{ $guest->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
            <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close m-2" style="float: right; font-size: 20px">
                  <i class="las la-times la-24-black"></i>
            </a><!-- .popup__close -->
         </span>
         <div class="modal-body">
            <h3 class="modal-header-title">Edit Guest</h3>
            <div class="login-form">
                  <div class="submit-section">
                     <div class="row">
                        <div class="form-group col-md-12">
                              <form action="{{ route('client.event.guest.registration.edit') }}" method="POST" enctype="multipart/form-data" >
                                 @csrf
                                 <input type="hidden" name="guest_id" value="{{ $guest->id }}" id="">
                                 <div class="row">
                                    <div class="col-lg-4">
                                       <div class="field-group field-input">
                                          <label class="label">Ticket *</label>
                                          <select name="ticket_title" class="form-control" id="ticket_edit" onchange="calculatePriceEdit()">
                                             <option value="General Admission" data-price="0" {{ $guest->ticket_title == 'General Admission' ? 'selected' : '' }}>General Admission</option>
                                             @foreach ($tickets as $ticket)
                                                <option value="{{ $ticket->title }}" data-price="{{ $ticket->price }}" {{ $guest->ticket_title == $ticket->title ? 'selected' : '' }}>{{ $ticket->title }} ({{ $ticket->price }})</option>
                                             @endforeach
                                          </select>
                                          <x-input-error for="ticket_title"></x-input-error>
                                       </div>
                                    </div>
                                    <input type="hidden" name="ticket_price" id="ticket_price_edit">
                                    <div class="col-lg-4">
                                          <div class="field-group field-input">
                                             <label>No. of Guests</label>
                                             <input type="number" min="1" name="guests" class="form-control" value="{{ $guest->guests }}" id="guest_number_edit">
                                             <x-input-error for="guests"></x-input-error>
                                          </div>
                                    </div>
                                    <div class="col-lg-4">
                                          <div class="field-group field-input">
                                             <label>Total Amount</label>
                                             <input type="number" name="amount" value="{{ $guest->amount }}" id="amount_edit" readonly>
                                             <x-input-error for="amount"></x-input-error>
                                          </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-lg-6">
                                          <div class="field-group field-input">
                                             <label>Guest's Name *</label>
                                             <input type="text" name="names" id="" class="form-control" placeholder="Full Name" value="{{ $guest->names }}" required>
                                             <x-input-error for="names"></x-input-error>
                                          </div>
                                    </div>
                                    <div class="col-lg-6">
                                          <div class="field-group field-input">
                                             <label>Phone Number</label>
                                             <input type="phone_number" name="phone_number" class="form-control" placeholder="Phone Number" value="{{ $guest->phone_number }}">
                                             <x-input-error for="phone_number"></x-input-error>
                                          </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-lg-6">
                                       <div class="field-group field-input">
                                          <label>Email</label>
                                          <input type="email" name="email" class="form-control" id="" placeholder="Email Address" value="{{ $guest->email }}">
                                          <x-input-error for="email"></x-input-error>
                                       </div>
                                    </div>
                                    <div class="col-lg-6">
                                       <div class="field-group field-input">
                                          <label>Company</label>
                                          <input type="text" name="company" class="form-control" id="" placeholder="Company/Organization" value="{{ $guest->company }}">
                                          <x-input-error for="company"></x-input-error>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-lg-6">
                                          <div class="field-group field-input">
                                             <label>Address</label>
                                             <input type="address" name="address" class="form-control" value="{{ $guest->address }}">
                                             <x-input-error for="address"></x-input-error>
                                          </div>
                                    </div>
                                    <div class="col-lg-6">
                                       <div class="field-group field-input">
                                          <label>Notes</label>
                                          <textarea name="notes" class="form-control" placeholder="Notes">{{ $guest->notes }}</textarea>
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
      $('#guest_number_edit').on('input', function() {
         calculatePriceEdit()
      })

      function calculatePriceEdit() {
         ticket_price = $('#ticket_edit').find(':selected').data('price')
         $('#ticket_price_edit').val(ticket_price)
         guest_number = $('#guest_number_edit').val()
         let totalAmount = ticket_price * guest_number
         $('#amount_edit').val(totalAmount)
      }
   </script>
   @endpush
</div>

<!-- End Modal -->
