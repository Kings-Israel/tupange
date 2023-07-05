<div class="modal fade signup" id="response-{{ $issue->id }}" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
            <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
               <i class="las la-times la-24-black"></i>
            </a><!-- .popup__close -->
         </span>
         <div class="modal-body">
            <div class="login-form">
               <div class="row">
                  <form action="{{ route('resolution-center.response') }}" method="POST" enctype="multipart/form-data" id="issue-{{ $issue->id }}-response-form">
                     @csrf
                     <input type="hidden" name="issue_id" value="{{ $issue->id }}">
                     <div class="package-description">
                        <p>{{ $issue->issue }}</p>
                     </div>
                     <br>
                     <div class="field-group field-input">
                        <label class="label">Enter Your Response Here</label>
                        <textarea name="issue_response" class="issue-{{ $issue->id }}-response form-control" rows="5" minlength="3" required></textarea>
                        <x-input-error for="issue_response"></x-input-error>
                     </div>
                     <br>
                     <input type="submit" value="Submit" class="btn" id="issue-{{ $issue->id }}-response-submit-btn">
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
   @push('scripts')
      <script>
         function checkIfEmailInString(text) {
            var re = /(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/;
            return re.test(text.replace(/ /g,''));
         }

         function checkIfPhoneNumberInString(text) {
            var phoneExp = /(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?/img;
            return phoneExp.test(text.replace(/ /g,''))
         }

         $('#issue-'+{!! json_decode($issue->id) !!}+'-response-submit-btn').on('click', function (e) {
            e.preventDefault();
            let message = $('.issue-'+{!! json_decode($issue->id) !!}+'-response').val()
            if (checkIfEmailInString(message)) {
               toastr.options =
                  {
                     "closeButton" : true,
                     "progressBar" : true,
                     "positionClass" : "toast-bottom-right"
                  }
               toastr.error("You cannot share contact information!!");

               return
            }
            if (checkIfPhoneNumberInString(message)) {
               toastr.options =
                  {
                     "closeButton" : true,
                     "progressBar" : true,
                     "positionClass" : "toast-bottom-right"
                  }
               toastr.error("You cannot share contact information!!");

               return
            }

            $('#issue-'+{!! json_decode($issue->id) !!}+'-response-form').submit()
         })
      </script>
   @endpush
</div>
<!-- End Modal -->
