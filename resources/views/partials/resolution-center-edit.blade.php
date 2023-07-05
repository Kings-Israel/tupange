<div class="modal fade signup" id="update-{{ $issue->id }}" tabindex="-1" role="dialog" aria-hidden="true">
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
                  <form action="{{ route('resolution-center.update', $issue->id) }}" method="POST" enctype="multipart/form-data" id="update-issue-{{ $issue->id }}-form" >
                     @csrf
                     @method('PUT')
                     <div class="field-group field-input">
                        <label class="label">Update Issue</label>
                        <textarea name="issue" class="updated-issue-{{ $issue->id }} form-control" rows="5" minlength="3" required>{{ $issue->issue }}</textarea>
                        <x-input-error for="issue"></x-input-error>
                     </div>
                     <br>
                     <input type="submit" value="Submit" class="btn" id="update-issue-{{ $issue->id }}-submit-btn">
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
         $('#update-issue-'+{!! json_encode($issue->id) !!}+'-submit-btn').on('click', function (e) {
            e.preventDefault();
            let message = $('.updated-issue-'+{!! json_encode($issue->id) !!}).val()
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

            $('#update-issue-'+{!! json_encode($issue->id) !!}+'-form').submit()
         })
      </script>
   @endpush
</div>

<!-- End Modal -->
