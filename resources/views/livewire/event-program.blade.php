<div>
   <div class="member-filter">
      <div class="mf-left">
         <form action="#" method="GET">
            <div class="row">
               <div class="col-md-4 col-sm-12">
                  <div class="field-select">
                     <select name="place_cities" wire:model="perPage">
                        <option value="20">Show 20</option>
                        <option value="40">Show 40</option>
                     </select>
                     <i class="la la-angle-down"></i>
                  </div>
               </div>
               @if ($event_types->count())
                  <div class="col-md-4 col-sm-12">
                     <div class="field-select">
                        <select name="place_cities" wire:model="eventType">
                           <option value="">All</option>
                           @foreach ($event_types as $type)
                              <option value="{{ $type }}">{{ $type }}</option>
                           @endforeach
                        </select>
                        <i class="la la-angle-down"></i>
                     </div>
                  </div>
               @endif
               <div class="col-md-4 col-sm-12">
                  <div class="field-group field-input">
                     <input wire:model="search" type="text" id="service-title-search" name="s" placeholder="Search Event Name">
                  </div><!-- .search__input -->
               </div>
            </div>
         </form>
      </div><!-- .mf-left -->
      <div class="mf-right">
         <div class="right-header__button btn">
            <a title="Create Your Own Event" href="{{ route('events.index') }}">
               <span>Events</span>
            </a>
         </div>
         <div class="right-header__button btn btn-info" style="background: #1DA1F2">
            <a title="Create Your Own Event" href="{{ route('client.program.create') }}">
               <span>Create Program</span>
            </a>
         </div>
      </div><!-- .mf-right -->
   </div><!-- .member-filter -->
   <table class="member-place-list owner-booking table-responsive">
      <thead>
      <tr>
         <th>#</th>
         <th class="table-width-200">Event Type</th>
         <th class="table-width-200">Event Name</th>
         <th class="table-width-200">Starts At</th>
         <th class="table-width-200">Ends At</th>
         <th class="table-width-250">Action</th>
      </tr>
      </thead>
      <tbody>
      @foreach($programs as $eventProgram)
         @include('partials.program-payment')
         @include('partials.share-program')
         @include('partials.delete-event-program')
         <tr>
            <td>{{ $i++ }}</td>
            <td data-title="Event Type" class="table-width-200"><a href="{{ route('client.program.show', $eventProgram->id) }}">{{ $eventProgram->event_type }}</a></td>
            <td data-title="Event Name" class="table-width-200">{{ $eventProgram->event_name }}</td>
            <td data-title="Event Date" class="table-width-200">{{ Carbon\Carbon::parse($eventProgram->start_date)->format('M d, Y H:i') }}</td>
            <td data-title="Event Date" class="table-width-200">{{ Carbon\Carbon::parse($eventProgram->end_date)->format('M d, Y H:i') }}</td>
            <td class="table-width-250">
               <a href="{{ route('client.program.edit', $eventProgram->id) }}" class="edit" title="Edit"><i class="las la-edit"></i></a>
               <a href="{{ route('client.program.show', $eventProgram->id) }}" class="view" title="View"><i class="la la-eye"></i></a>
               @if ($eventProgram->canDownload == false)
                  <span class="view" data-bs-toggle="modal" data-bs-target="#program-{{ $eventProgram->id }}" title="Download"><i class="la la-download"></i></span>
                  <span class="view" data-bs-toggle="modal" data-bs-target="#program-{{ $eventProgram->id }}" title="Share"><i class="las la-share"></i></span>
               @else
                  <a href="{{ route('client.program.pdf', $eventProgram) }}" title="Download"><i class="la la-download"></i></a>
                  <span data-bs-toggle="modal" data-bs-target="#share-program-{{ $eventProgram->id }}" title="Share"><i class="las la-share"></i></span>
               @endif
               <i class="la la-trash" data-bs-toggle="modal" data-bs-target="#delete-event-program-{{ $eventProgram->id }}" title="Delete"></i>
            </td>
         </tr>
      @endforeach
      </tbody>
   </table>
   {{ $programs->links() }}
</div>

