<div class="modal fade signup" id="view-task-{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
               <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                  <i class="las la-times la-24-black"></i>
               </a><!-- .popup__close -->
         </span>
         <div class="modal-body">
            <h2 class="modal-header-title">{{ $task->task }}</h2>
            <div class="login-form">
               <div class="submit-section">
                  <div class="row">
                     <div class="form-group col-md-12">
                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                        <div class="row">
                           <div class="col-6">
                              <h4 class="label">Person Responsible:</h4>
                           </div>
                           <div class="col-6">
                              <h5>{{ $task->person_responsible }}</h5>
                           </div>
                        </div>
                        <br>
                        <div class="row">
                           <div class="col-6">
                              <h4 for="company_logo">Date Due</h4>
                           </div>
                           <div class="col-6">
                              <h5>{{ Carbon\Carbon::parse($task->date_due)->format('M d, Y') }}</h5>
                           </div>
                        </div>
                        @if ($task->task_category)
                           <br>
                           <div class="row">
                              <div class="col-6">
                                 <h4 class="label">Category:</h4>
                              </div>
                              <div class="col-6">
                                 <h5>{{ $task->task_category }}</h5>
                              </div>
                           </div>
                        @endif
                        <br>
                        <div class="row">
                           <div class="col-6">
                              <h4 class="label">Status</h4>
                           </div>
                           <div class="col-6">
                              <h5>{{ $task->status }}</h5>
                           </div>
                        </div>
                        <br>
                        @if ($task->status !== 'Complete' && $task->status !== 'Closed')
                           <div class="d-flex justify-content-between">
                              <button class="btn send-reminder-btn-{{ $task->id }}" data-id="{{ $task->id }}" onclick="sendReminder({{ $task->id }})">Send Reminder</button>
                              <p class="sending-reminder-text-{{ $task->id }}" hidden>Sending. Please Wait...</p>
                           </div>
                        @endif
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- End Modal -->
