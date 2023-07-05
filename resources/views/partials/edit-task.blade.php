<div class="modal fade signup" id="edit-task-{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
               <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                  <i class="las la-times la-24-black"></i>
               </a><!-- .popup__close -->
         </span>
         <div class="modal-body">
               <h3 class="modal-header-title">Edit Task</h3>
               <div class="login-form">
                  <div class="submit-section">
                     <div class="row">
                           <div class="form-group col-md-12">
                              <form action="{{ route('client.event.tasks.edit') }}" method="POST" enctype="multipart/form-data" >
                                 @csrf
                                 <input type="hidden" name="task_id" value="{{ $task->id }}">
                                 <div class="row">
                                       <div class="col-lg-6">
                                          <div class="field-group field-input">
                                             <label for="company_logo">Task</label>
                                             <input type="text" name="task_name" class="form-control" value="{{ $task->task }}" required>
                                             <x-input-error for="task_name"></x-input-error>
                                          </div>
                                       </div>
                                       <div class="col-lg-6">
                                          <div class="field-group field-input">
                                             <h4 class="label">Person Responsible</h4>
                                             <select name="person_responsible" class="form-control">
                                                   <option value="">(Assign Person) None</option>
                                                   <option value="{{ $event->user->f_name }} {{ $event->user->l_name }}" {{ $task->person_responsible == $event->user->f_name.' '.$event->user->l_name ? 'selected' : '' }}>{{ $event->user->f_name }} {{ $event->user->l_name }} (You)</option>
                                                   @if ($eventUsers->count())
                                                      @foreach ($eventUsers as $user)
                                                         <option value="{{ $user->names }}" {{ $task->person_responsible == $user->names ? 'selected' : '' }}>{{ $user->names }}</option>
                                                      @endforeach
                                                    @endif
                                             </select>
                                             <x-input-error for="person_responsible"></x-input-error>
                                          </div>
                                       </div>
                                 </div>
                                 <div class="row">
                                       <div class="col-lg-6">
                                          <div class="field-group field-input">
                                             <label for="company_logo">Date Due</label>
                                             <input type="date" name="date_due" class="form-control" value="{{ $task->date_due }}" required>
                                             <x-input-error for="date_due"></x-input-error>
                                          </div>
                                       </div>
                                       <div class="col-lg-6">
                                          <div class="field-group field-input">
                                             <h4 class="label">Notify Due</h4>
                                             <select name="notify_due" class="form-control">
                                                   <option value="Never" {{ $task->notify_due == 'Never' ? 'selected' : '' }}>Never</option>
                                                   <option value="Daily" {{ $task->notify_due == 'Daily' ? 'selected' : '' }}>Daily</option>
                                                   <option value="Weekly" {{ $task->notify_due == 'Weekly' ? 'selected' : '' }}>Weekly</option>
                                                   <option value="Monthly" {{ $task->notify_due == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                                             </select>
                                             <x-input-error for="notify_due"></x-input-error>
                                          </div>
                                       </div>
                                 </div>

                                 <h4 class="label">Category</h4>
                                 <div class="field-group field-input">
                                    <select name="task_category" class="form-control">
                                          <option value="">Select Category</option>
                                          @foreach ($categories as $category)
                                             <option value="{{ $category->name }}" {{ $category->name === $task->task_category ? 'selected' : '' }}>{{ $category->name }}</option>
                                          @endforeach
                                    </select>
                                    <x-input-error for="task_category"></x-input-error>
                                 </div>

                                 <h4 class="label">Status</h4>
                                 <select name="status" class="form-control">
                                       <option value="Open" {{ $task->status == 'Open' ? 'selected' : '' }}>Open</option>
                                       <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                       <option value="Complete" {{ $task->status == 'Complete' ? 'selected' : '' }}>Complete</option>
                                       <option value="Closed" {{ $task->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                                 </select>
                                 <x-input-error for="status"></x-input-error>
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
</div>

<!-- End Modal -->
