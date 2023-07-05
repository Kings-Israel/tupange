<div class="modal fade signup" id="upload-guests-{{ $event->id }}" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
            <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
               <i class="las la-times la-24-black"></i>
            </a><!-- .popup__close -->
         </span>
         <div class="modal-body">
            <h3 class="modal-header-title">Upload Guest List</h3>
            <div class="login-form">
               <div class="submit-section">
                  <div class="row">
                     <div class="form-group col-md-12">
                        <form action="{{ route('client.event.guest.add.upload', $event) }}" method="POST" enctype="multipart/form-data" id="upload_guest_list_{{ $event->id }}" >
                           @csrf
                           <input type="hidden" name="event_id" value="{{ $event->id }}">
                           <div class="field-group field-input w-50">
                              <label for="">Select File</label>
                              <input type="file" name="uploaded_file" class="form-control" accept=".xls, .xlsx, .csv" required>
                              <x-input-error for="uploaded_file"></x-input-error>
                           </div>
                           <br>
                           <input type="submit" value="Submit" class="btn" id="btn_submit_{{ $event->id }}">
                        </form>
                        <br>
                        <div class="field-group field-note">
                           NOTE: The Columns should be in the following order: <br>
                           Barcode(Any Random Letters and Numbers), Status(Default, Invited, Confirmed, Attended)<b>*</b>, First Name<b>*</b>, Last Name<b>*</b>, Guest's Category, Phone Number, Email, Company<b>**</b>, Address<b>**</b>, Diet Instructions, Table Number, Invitation Phone Number, Invitation Email, Custom Question, Custom Answer, Guest's Role (Committee, Tasks)
                        </div>
                        <div class="field-group field-note">
                           <b>* Field is required</b><br>
                           <b>** For Corporate Events</b>
                        </div>
                        <br>
                        <div class="field-group field-note">
                           <p>
                              ***Make sure the columns have the headings of the respective data they hold. <br>
                              ***If there's no data for the column, leave the cell blank
                           </p>
                        </div>
                        <div class="sample">
                           <a href="{{ route('client.event.guest-list.sample') }}" class="btn btn-primary download-sample">
                              Download Sample
                           </a>
                           * This sample file can be edited and uploaded.
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
