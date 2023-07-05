<div>
   <div class="member-filter">
      <div class="mf-left">
         <form action="#" method="GET">
            <div class="row">
               <div class="col-lg-4 col-md-12 col-sm-12">
                  <div class="field-select">
                     <select name="place_cities" wire:model="perPage">
                        <option value="20">Show 10</option>
                        <option value="20">Show 20</option>
                        <option value="20">Show 40</option>
                        <option value="40">Show 60</option>
                        <option value="40">Show 100</option>
                     </select>
                     <i class="la la-angle-down"></i>
                  </div>
               </div>
               <div class="col-lg-4 col-md-12 col-sm-12">
                  <div class="field-select">
                     <select name="place_cities" wire:model="status">
                        <option value="All">All status</option>
                        <option value="Active">Active</option>
                        <option value="Past">Past</option>
                        <option value="Live">Live</option>
                     </select>
                     <i class="la la-angle-down"></i>
                  </div>
               </div>
               <div class="col-lg-4 col-md-12 col-sm-12">
                  <div class="field-group field-input">
                     <input type="phone_number" wire:model="search" class="form-control" placeholder="Search Name">
                  </div>
               </div>
            </div>
         </form>
      </div><!-- .mf-left -->
      <div class="mf-right">
         @if (Auth::user()->status === 'user' && Auth::user()->hasAssignedRoles())
            <div class="right-header__button btn" style="background-color: #000">
               <a href="{{ route('client.events.roles') }}">
                  Events With Roles
               </a>
            </div>
            @endif
         <div class="right-header__button btn">
            <a title="Create Your Own Event" href="{{ route('events.create') }}">
               <span>Create Event</span>
            </a>
         </div>
         <div class="right-header__button btn" style="background-color: #F58C1C">
            <a title="Create Your Own Event" href="{{ route('client.programs.index') }}">
               <span>My Programs</span>
            </a>
         </div>
      </div><!-- .mf-right -->
   </div><!-- .member-filter -->
   <table class="member-place-list owner-booking table-responsive">
      <thead>
      <tr>
         <th>#</th>
         <th class="table-width-200">Cover Photo</th>
         <th class="table-width-150">Event Name</th>
         {{-- <th class="table-width-200">Event Description</th> --}}
         <th class="table-width-200">Event Date</th>
         <th>Event Type</th>
         <th>Status</th>
         <th>Action</th>
      </tr>
      </thead>
      <tbody>
      @foreach($events as $event)
         <tr>
            <td>{{ $i++ }}</td>
            <td data-title="Cover Photo" class="service-img table-width-200">
               <a href="{{route('events.show', $event->id)}}">
                  <img src="{{ $event->getEventCoverImage($event->event_poster) }}" style="border-radius: 5px;" onerror="this.onerror=null; this.src='{{ $event->user->getAvatar($event->user->avatar) }}'" />
               </a>
            </td>
            <td data-title="Title" class="table-width-150">
               <a href="{{ route('events.show', $event->id) }}" style="cursor: pointer">
                  {{ $event->event_name }}
               </a>
            </td>
            <td data-title="Event Date" class="table-width-200">{{ Carbon\Carbon::parse($event->event_start_date)->format('M d, Y') }}</td>
            {{-- <td data-title="Description" class="event-description table-width-200">{{ $event->event_description }}</td> --}}
            <td data-title="Type">{{ $event->event_type }}</td>
            @if ($event->getEventStatus() == 'Active')
               <td data-title="Status" class="active">{{ $event->getEventStatus() }}</td>
            @elseif ($event->getEventStatus() == 'Live')
               <td data-title="Status" class="approved">{{ $event->getEventStatus() }}</td>
            @elseif ($event->getEventStatus() == 'Past')
               <td data-title="Status" class="cancel">{{ $event->getEventStatus() }}</td>
            @endif
            <td>
               <a href="{{ route('events.edit', $event->id) }}" class="edit" title="Edit"><i class="las la-edit"></i></a>
               <a href="{{ route('events.show', $event->id) }}" class="view" title="View"><i class="la la-eye"></i></a>
               <a href="{{ route('events.destroy', $event->id) }}" wire:click.prevent="deleteEvent({{ $event->id }})" class="delete" title="Delete"><i class="la la-trash"></i></a>
            </td>
         </tr>
      @endforeach
      </tbody>
   </table>
   {{ $events->links() }}
</div>
