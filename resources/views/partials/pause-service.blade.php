<div class="modal fade signup" id="pause-service-{{ $service->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
            <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                  <i class="las la-times la-24-black"></i>
            </a><!-- .popup__close -->
         </span>
         <div class="modal-body">
            <h3 class="modal-header-title">Pause Service {{ $service->service_title }}</h3>
            <div class="login-form">
               <div class="submit-section">
                  <form action="{{ route('vendor.service.pause') }}" method="post" id="pause-service">
                     @csrf
                     <input type="hidden" name="service_id" value="{{ $service->id }}">
                     <div class="row mb-2">
                        <div class="field-group field-input col-sm-12 col-md-6">
                           <label>Pause From</label>
                           <input type="date" placeholder="DD/MM/YYYY" class="form-control" name="pause_from" id="pause_from" value="{{ old('pause_from') }}">
                           <x-input-error id="pause_fromError" for="pause_from"></x-input-error>
                        </div>
                        <div class="field-group field-input col-sm-12 col-md-6">
                           <label>Pause Until</label>
                           <input type="date" placeholder="DD/MM/YYYY" name="pause_until" class="form-control" id="pause_until" value="{{ old('pause_until') }}">
                           <x-input-error id="pause_untilError" for="pause_until"></x-input-error>
                        </div>
                     </div>
                     <div class="field-group field-input">
                        <label>Pause Note</label>
                        <textarea name="pause_note" class="form-control" rows="3" cols="50" placeholder="Enter a description to note the reason for pausing this service..."></textarea>
                     </div>
                     <br>
                     <input type="hidden" name="action" id="action">
                     <div id="submit-buttons-section">
                        <div style="float: right;">
                           <input type="submit" class="btn" id="pause" value="Pause"/>
                           <input type="submit" class="btn" id="pause-indefinitely" value="Pause Indefinitely"/>
                        </div>
                        <span data-bs-dismiss="modal" class="popup__close" style="cursor: pointer; color: rgb(254, 49, 49);">Cancel</span>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
   @push('scripts')
      <script>
         var today = new Date();
         var dd = today.getDate();
         var mm = today.getMonth()+1; //January is 0 so need to add 1 to make it 1!
         var yyyy = today.getFullYear();
         if(dd<10){
            dd='0'+dd
         }
         if(mm<10){
            mm='0'+mm
         }

         today = yyyy+'-'+mm+'-'+dd;
         let pauseFrom = $('#pause_from')
         let pauseUntil = $('#pause_until')
         let action = $('#action')

         pauseFrom.attr('min', today)
         pauseUntil.attr('min', today)


         pauseFrom.on('change', function () {
            let min_end_date = $(this).val()
            pauseUntil.attr('min', min_end_date)
         })

         $('#pause').on('click', function(e) {
            e.preventDefault();
            action.val('Pause')
            let formData = $('#pause-service').serializeArray();

            $.ajax({
               method: "POST",
               dataType: "json",
               headers: {
                  Accept: "application/json"
               },
               url: "{{ route('vendor.service.pause') }}",
               data: formData,
               success: ({ redirectPath }) => {
                  window.location.assign(redirectPath)
               },
               error: (response) => {
                  toastr.options =
                        {
                           "closeButton" : true,
                           "progressBar" : true,
                           "positionClass" : "toast-bottom-right"
                        }
                  toastr.error("Please select all the dates");
               }
            })
         })

         $('#pause-indefinitely').on('click', function(e) {
            e.preventDefault();
            action.val('Pause Indefinitely')
            let formData = $('#pause-service').serializeArray();

            $.ajax({
               method: "POST",
               dataType: "json",
               headers: {
                  Accept: "application/json"
               },
               url: "{{ route('vendor.service.pause') }}",
               data: formData,
               success: ({ redirectPath }) => {
                  window.location.assign(redirectPath)
               },
               error: (response) => {
                  toastr.options =
                        {
                           "closeButton" : true,
                           "progressBar" : true,
                           "positionClass" : "toast-bottom-right"
                        }
                  toastr.error("An error occured. Please refresh page and try again.");
               }
            })
         })
      </script>
   @endpush
</div>

<!-- End Modal -->
