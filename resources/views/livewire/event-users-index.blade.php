<div>
   <main id="main" class="site-main">
      <div class="site-content owner-content">
         <div class="container">
            <div class="member-place-wrap">
               <div class="member-wrap-top mt-2">
                  <h2>Committee</h2>
                  <div class="header-section">
                     <a class="btn" href="{{ route('events.show', $event) }}">Back to My Event</a>
                     <button class="btn" data-bs-toggle="modal" data-bs-target="#add-user-{{ $event->id }}" style="background-color: #F58C1C">Add Member</button>
                     @include('partials.add-user')
                  </div>
               </div><!-- .member-place-wrap -->
               <table class="member-place-list owner-booking table-responsive">
                  <thead>
                     <tr>
                        <th>#</th>
                        <th class="table-width-200">User Name</th>
                        <th>Email</th>
                        <th class="table-width-250">Role</th>
                        <th>Invite Sent Status</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td data-title="ID">
                           <p>{{ $i++ }}</p>
                        </td>
                        <td data-title="Names" class="table-width-200">
                           {{$event->user->name }}(You)
                        </td>
                        <td data-title="Email">
                           <span>{{ $event->user->email }}</span>
                        </td>
                        <td data-title="Role" class="table-width-250">
                           <span>Manage All</span>
                        </td>
                     </tr>
                     @foreach ($eventUsers->event_users as $user)
                        @include('partials.edit-event-user')
                        <tr>
                           <td data-title="ID">
                              <p>{{ $i++ }}</p>
                           </td>
                           <td data-title="Names" class="table-width-200">
                              {{$user->names }}
                           </td>
                           <td data-title="Email">
                              <span>{{ $user->email }}</span>
                           </td>
                           <td data-title="Role" class="table-width-250">
                              <span>{{ $user->role }}</span>
                           </td>
                           <td data-title="Invite Sent">
                              <span>{{ $user->isSent == 1 ? 'Sent' : 'Not Sent' }}</span>
                           </td>
                           <td>
                              @if ($user->isSent == 0)
                                 <span wire:loading.remove wire:click.prevent="sendInvite({{ $user }})">
                                    <i data-title="Send Invite" class="fa fa-paper-plane" style="font-size: 18px"></i>
                                 </span>
                                 @endif

                                 <span data-bs-toggle="modal" data-bs-target="#edit-event-user-{{ $user->id }}">
                                    <i class="las la-edit"></i>
                                 </span>

                                 <span wire:click.prevent="deleteUser({{ $user }})">
                                    <i class="las la-trash"></i>
                                 </span>
                              </td>
                           </tr>
                     @endforeach
                  </tbody>
               </table>
            </div><!-- .member-wrap-top -->
            <div wire:loading>
               <x-loader></x-loader>
            </div>
          </div>
      </div><!-- .site-content -->
  </main><!-- .site-main -->
</div>
