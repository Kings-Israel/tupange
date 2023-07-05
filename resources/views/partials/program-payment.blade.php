<div class="modal fade signup" id="program-{{ $eventProgram->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
            <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                  <i class="las la-times la-24-black"></i>
            </a><!-- .popup__close -->
         </span>
         <div class="modal-body">
            <h3 class="modal-header-title">Download or Share {{ $eventProgram->event_name }}'s Program</h3>
            <p>Pay Ksh.100 to download or share the event's program.</p>
            <div class="login-form">
               <div class="submit-section">
                  <form action="{{ route('client.program.pay') }}" method="post" id="program-payment-form-{{ $eventProgram->id }}">
                     @csrf
                     <input type="hidden" name="Lite_ConsumerOrderID_PreFix" value="PRM">
                     <input type="hidden" name="Ecom_SchemaVersion" />
                     <input type="hidden" name="Ecom_TransactionComplete" value="false" />
                     <input type="hidden" name="Lite_Authorisation" value="false" />
                     <input type="hidden" name="Ecom_Payment_Card_Protocols" value="iVeri" />
                     <input type="hidden" name="Lite_Order_Amount" value="10000">
                     <input type="hidden" name="Ecom_BillTo_Online_Email" value="{{ auth()->user()->email }}">
                     <input type="hidden" name="total_price" value="100">
                     <input type="hidden" name="program_id" value="{{ $eventProgram->id }}">
                     <div class="row d-none">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                           <div class="field-group field-input">
                              <label for="Payment Option" class="label">Select your payment option</label>
                              <select name="payment_method" id="payment_method_{{ $eventProgram->id }}" class="form-control" onchange="selectedPaymentMethod({{ $eventProgram->id }})">
                                 <option value="Debit Card" {{ old('payment_method') == 'Debit Card' ? 'selected' : '' }} selected>Debit Card</option>
                                 <option value="Mpesa" {{ old('payment_method') == 'Mpesa' ? 'selected' : '' }}>Mpesa</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                           <div class="field-group field-input" id="phone_number_input_{{ $eventProgram->id }}" hidden>
                              <label for="Phone Number" class="label">Enter the phone number here</label>
                              <input type="phone_number" name="phone_number" class="form-control" id="phone_number_{{ $eventProgram->id }}" value="{{ old('phone_number') }}">
                              <p class="error" hidden>Please enter the phone number.</p>
                           </div>
                        </div>
                     </div>
                     <x-input-error for="phone_number"></x-input-error>
                     <br>
                     <input type="submit" class="btn" style="float:right" id="program-pay-btn-{{ $eventProgram->id }}" value="Pay"/>
                     <span data-bs-dismiss="modal" class="popup__close" style="cursor: pointer;">Cancel</span>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
   @push('scripts')
      <script>
         function selectedPaymentMethod(id) {
            var method = $('#payment_method_'+id).val()
            if (method == 'Mpesa') {
               $('#phone_number_input_'+id).removeAttr('hidden')
               $('#phone_number_'+id).attr('required', true)
            } else {
               $('#phone_number_input_'+id).attr('hidden', true)
               $('#phone_number_'+id).removeAttr('required')
            }
         }

         $('#program-pay-btn-'+{{ json_encode($eventProgram->id) }}).on('click', function(e) {
            e.preventDefault()

            var paymentMethod = $('#payment_method_'+{{ json_encode($eventProgram->id) }}).val()
            var phone_number = $('#phone_number_'+{{ json_encode($eventProgram->id) }}).val()

            if (paymentMethod == 'Mpesa') {
               if (phone_number == '') {
                  $('.error').removeAttr('hidden')
                  setTimeout(() => {
                     $('.error').attr('hidden', true)
                  }, 5000);
               } else {
                  $('#program-payment-form-'+{{ json_encode($eventProgram->id) }}).submit()
               }
            } else if(paymentMethod == 'Debit Card') {
               // $('#program-payment-form-'+{{ json_encode($eventProgram->id) }}).attr('action', 'https://portal.host.iveri.com/Lite/Authorise.aspx')
               $('#program-payment-form-'+{{ json_encode($eventProgram->id) }}).submit()
            }
         })
      </script>
   @endpush
</div>

<!-- End Modal -->
