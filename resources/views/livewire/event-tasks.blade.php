<div>
    <main id="main" class="site-main">
        <div class="site-content owner-content">
            <div class="container">
                <div class="member-place-wrap">
                    <div class="member-wrap-top">
                        <h2>Manage Tasks</h2>
                        <div>
                            <a class="btn" href="{{ route('events.show', $event) }}">Back to My Event</a>
                            <button class="btn m-2" data-bs-toggle="modal" data-bs-target="#add-task-{{ $event->id }}" style="background-color: #F58C1C">Add Tasks</button>
                            <button class="btn" data-bs-toggle="modal" data-bs-target="#add-user-{{ $event->id }}" hidden id="add-event-user-btn"></button>
                            @include('partials.add-task')
                            @include('partials.add-user')
                        </div>
                    </div><!-- .member-place-wrap -->
                    <table class="member-place-list owner-booking table-responsive">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="table-width-150">Task</th>
                                <th>Person Responsible</th>
                                <th>Category</th>
                                <th class="table-width-150">Notify Due</th>
                                <th>Date Due</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tasks as $task)
                                @include('partials.edit-task')
                                @include('partials.view-task')
                                <tr>
                                    <td data-title="ID">
                                        <p>{{ $i++ }}</p>
                                    </td>
                                    <td data-title="Task" class="table-width-150">
                                        {{$task->task }}
                                    </td>
                                    <td data-title="Responsible">
                                        <span>{{ $task->person_responsible }}</span>
                                    </td>
                                    <td>
                                       <span>{{ $task->task_category }}</span>
                                    </td>
                                    <td data-title="Notify Due" class="table-width-150">
                                        <p>{{ $task->notify_due }}</p>
                                    </td>
                                    <td data-title="Date Due">
                                        {{ Carbon\Carbon::parse($task->date_due)->format('M d, Y') }}
                                    </td>
                                    <td data-title="Status">
                                        <span>{{ $task->status }}</span>
                                    </td>
                                    <td>
                                       <div class="tasks-buttons">
                                          <span data-bs-toggle="modal" data-bs-target="#view-task-{{ $task->id }}"><i class="la la-eye"></i></span>
                                          <span data-bs-toggle="modal" data-bs-target="#edit-task-{{ $task->id }}">
                                             <i class="las la-edit"></i>
                                          </span>
                                          <span wire:click.prevent="deleteTask({{ $task }})">
                                             <i class="las la-trash"></i>
                                          </span>
                                       </div>
                                    </td>
                                </tr>
                            @endforeach
                            <form action="{{ route('client.event.tasks.add') }}" method="POST" class="add-task-form">
                              @csrf
                              <input type="hidden" name="event_id" value="{{ $event->id }}">
                              <tr>
                                 <td data-title="ID">
                                    <p></p>
                                 </td>
                                 <td class="table-width-150">
                                    <div class="field-group field-input">
                                       <input type="text" name="task_name[0]" class="form-control" placeholder="Task" required>
                                       <x-input-error for="task_name[0]"></x-input-error>
                                    </div>
                                 </td>
                                 <td data-title="Responsible">
                                    <div class="field-group field-input">
                                       <select name="person_responsible[0]" class="form-control" id="assign-user-input">
                                             <option value="">(Assign Person) None</option>
                                             <option value="{{ $event->user->f_name }} {{ $event->user->l_name }}">{{ $event->user->f_name }} {{ $event->user->l_name }}(You)</option>
                                             @if ($eventUsers->count())
                                                @foreach ($eventUsers as $user)
                                                   <option value="{{ $user->names }}">{{ $user->names }}</option>
                                                @endforeach
                                             @endif
                                             <option value="New User" style="background-color: gray">Add New User</option>
                                       </select>
                                       <x-input-error for="person_responsible[0]"></x-input-error>
                                    </div>
                                 </td>
                                 <td>
                                    <div class="field-group field-input">
                                       <select name="task_category[0]" class="form-control">
                                             <option value="">Select Category</option>
                                             @foreach ($categories as $category)
                                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                                             @endforeach
                                       </select>
                                       <x-input-error for="category[0]"></x-input-error>
                                    </div>
                                 </td>
                                 <td data-title="Notify Due">
                                    <div class="field-group field-input">
                                       <select name="notify_due[0]" class="form-control">
                                          <option value="Never">Never</option>
                                          <option value="Daily">Daily</option>
                                          <option value="Weekly">Weekly</option>
                                          <option value="Monthly">Mothly</option>
                                       </select>
                                       <x-input-error for="notify_due[0]"></x-input-error>
                                    </div>
                                 </td>
                                 <td data-title="Date Due">
                                    <div class="field-group field-input">
                                       <input type="date" name="date_due[0]" class="form-control" required>
                                       <x-input-error for="date_due"></x-input-error>
                                    </div>
                                 </td>
                                 <td data-title="Status">
                                    <div class="field-group field-input">
                                       <select name="status[0]" class="form-control">
                                             <option value="Open">Open</option>
                                             <option value="In Progress">In Progress</option>
                                             <option value="Complete">Complete</option>
                                             <option value="Closed">Closed</option>
                                       </select>
                                       <x-input-error for="status[0]"></x-input-error>
                                    </div>
                                 </td>
                                 <td class="submit-btn-container">
                                    <button class="submit-task"><i class="las la-save"></i></button>
                                 </td>
                              </tr>
                           </form>
                        </tbody>
                     </table>
                     {{ $tasks->links() }}
                </div><!-- .member-wrap-top -->
                <div wire:loading>
                  <x-loader></x-loader>
               </div>
            </div>
        </div><!-- .site-content -->
    </main><!-- .site-main -->
</div>
