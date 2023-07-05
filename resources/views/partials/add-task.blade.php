<div class="modal fade signup" id="add-task-{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-xl modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
               <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                  <i class="las la-times la-24-black"></i>
               </a><!-- .popup__close -->
         </span>
         <div class="modal-body">
            <div class="row" id="add-task-header">
               <div class="col-6">
                  <h3 class="modal-header-title">Add Tasks</h3>
               </div>
               <div class="col-6">
                  <button class="btn btn-sm btn-info" id="add_task"><i class="fa fas-plus"></i>Add Task</button>
               </div>
            </div>
            <div class="login-form">
               <div class="submit-section">
                  <div class="form-group col-md-12">
                     <form action="{{ route('client.event.tasks.add') }}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                        <div id="tasks">
                           <div class="row">
                              <div class="col-sm-12 col-md-6 col-lg-2">
                                 <div class="field-group field-input">
                                    <label for="">Task 1*</label>
                                    <input type="text" name="task_name[0]" class="form-control" required>
                                    <x-input-error for="task_name[0]"></x-input-error>
                                 </div>
                              </div>
                              <div class="col-sm-12 col-md-6 col-lg-2">
                                 <div class="field-group field-input">
                                    <h4 class="label">Person Responsible</h4>
                                    <select name="person_responsible[0]" class="form-control">
                                          <option value="">(Assign Person) None</option>
                                          <option value="{{ $event->user->f_name }} {{ $event->user->l_name }}">{{ $event->user->f_name }} {{ $event->user->l_name }}(You)</option>
                                          @if ($eventUsers->count())
                                          @foreach ($eventUsers as $user)
                                             <option value="{{ $user->names }}">{{ $user->names }}</option>
                                          @endforeach
                                          @endif
                                    </select>
                                    <x-input-error for="person_responsible[0]"></x-input-error>
                                 </div>
                              </div>
                              <div class="col-sm-12 col-md-6 col-lg-2">
                                 <div class="field-group field-input">
                                    <h4 class="label">Category</h4>
                                    <select name="task_category[0]" class="form-control">
                                          <option value="">Select Category</option>
                                          @foreach ($categories as $category)
                                             <option value="{{ $category->name }}">{{ $category->name }}</option>
                                          @endforeach
                                    </select>
                                    <x-input-error for="category[0]"></x-input-error>
                                 </div>
                              </div>
                              <div class="col-sm-12 col-md-6 col-lg-2">
                                 <div class="field-group field-input">
                                       <label for="">Date Due*</label>
                                       <input type="date" name="date_due[0]" class="form-control" required>
                                       <x-input-error for="date_due"></x-input-error>
                                 </div>
                              </div>
                              <div class="col-sm-12 col-md-6 col-lg-2">
                                 <div class="field-group field-input">
                                       <h4 class="label">Notify Due</h4>
                                       <select name="notify_due[0]" class="form-control">
                                          <option value="Never">Never</option>
                                          <option value="Daily">Daily</option>
                                          <option value="Weekly">Weekly</option>
                                          <option value="Monthly">Mothly</option>
                                       </select>
                                       <x-input-error for="notify_due[0]"></x-input-error>
                                 </div>
                              </div>
                              <div class="col-sm-12 col-md-12 col-lg-2">
                                 <h4 class="label">Status</h4>
                                 <select name="status[0]" class="form-control">
                                       <option value="Open">Open</option>
                                       <option value="In Progress">In Progress</option>
                                       <option value="Complete">Complete</option>
                                       <option value="Closed">Closed</option>
                                 </select>
                                 <x-input-error for="status[0]"></x-input-error>
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
   @push('scripts')
   <script>
      let i = 0;
      let tasks = $('#tasks')
      $(document.body).on('click', '#add_task', function() {
         i++
         y = i
         y++
         let html = '<div class="row mt-1">'
               html += '<div class="col-sm-12 col-md-6 col-lg-2">' // Task Input Row Div
               html += '<div class="field-group field-input">' // Task Input Div
               html += '<label for="">Task '+y+++'*</label>' // Task Name
               html += '<input type="text" name="task_name['+i+']" class="form-control" required>' //Task Name Input
               html += '<x-input-error for="task_name['+i+']"></x-input-error>'
               html += '</div>' // End Task Input Div
               html += '</div>' // End Task Input Row Div
               html += '<div class="col-sm-12 col-md-6 col-lg-2">' // Person Responsible Row Div
               html += '<div class="field-group field-input">' // Person Responsible Input Div
               html += '<h4 class="label">Person Responsible</h4>' // Person Responsible Input Label
               html += '<select name="person_responsible['+i+']" class="form-control">' // Person Responsible Select Start
               html += '<option value="">(Assign Person) None</option>'
               html += '<option value="'+{!! json_encode($event->user->f_name) !!}+' '+{!! json_encode($event->user->l_name) !!}+'">'+{!! json_encode($event->user->f_name) !!}+' '+{!! json_encode($event->user->l_name) !!}+'(You)</option>'
               @if ($eventUsers->count())
               @foreach ($eventUsers as $user)
                  html += '<option value="'+{!! json_encode($user->names) !!}+'">'+{!! json_encode($user->names) !!}+'</option>'
               @endforeach
               @endif
               html += '</select>' // Person Responsible Input Select End
               html += '<x-input-error for="person_responsible['+i+']"></x-input-error>' // Person Responsible Input error div
               html += '</div>' // End Person Responsible Input Div
               html += '</div>' // End Person Responsible Row Div
               html += '<div class="col-sm-12 col-md-6 col-lg-2">' // Category Input Start Row
               html += '<div class="field-group field-input">' // Category Input Start
               html += '<h4 class="label">Category</h4>' // Category Input Label
               html += '<select name="task_category['+i+']" class="form-control">' // Start Category Input Select
               html += '<option value="">Select Category</option>'
               @foreach ($categories as $category)
                  html += '<option value="'+{!! json_encode($category->name) !!}+'">'+{!! json_encode($category->name) !!}+'</option>'
               @endforeach
               html += '</select>' // End Category Input Select
               html += '<x-input-error for="task_category['+i+']"></x-input-error>' // Category Input Error Div
               html += '</div>' // End Category Input
               html += '</div>' //End Category Input Row
               html += '<div class="col-sm-12 col-md-6 col-lg-2">'
               html += '<div class="field-group field-input">'
               html += '<label for="">Date Due*</label>'
               html += '<input type="date" name="date_due['+i+']" class="form-control" required>'
               html += '<x-input-error for="date_due"></x-input-error>'
               html += '</div>'
               html += '</div>'
               html += '<div class="col-sm-12 col-md-6 col-lg-2">'
               html += '<div class="field-group field-input">'
               html += '<h4 class="label">Notify Due</h4>'
               html += '<select name="notify_due['+i+']" class="form-control">'
               html += '<option value="Never">Never</option>'
               html += '<option value="Daily">Daily</option>'
               html += '<option value="Weekly">Weekly</option>'
               html += '<option value="Monthly">Mothly</option>'
               html += '</select>'
               html += '<x-input-error for="notify_due['+i+']"></x-input-error>'
               html += '</div>'
               html += '</div>'
               html += '<div class="col-sm-12 col-md-12 col-lg-2">'
               html += '<h4 class="label">Status</h4>'
               html += '<select name="status['+i+']" class="form-control">'
               html += '<option value="Open">Open</option>'
               html += '<option value="In Progress">In Progress</option>'
               html += '<option value="Complete">Complete</option>'
               html += '<option value="Closed">Closed</option>'
               html += '</select>'
               html += '<x-input-error for="status['+i+']"></x-input-error>'

               $(html).appendTo(tasks)
      })
   </script>
   @endpush
</div>

<!-- End Modal -->
