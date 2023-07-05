@extends('layouts.master')
@section('title', 'Events')
@section('content')
   <div class="container">
      <div class="member-place-wrap">
         <div class="member-wrap-top">
            <h2>Events with Roles</h2>
         </div>
         <div>
            <table class="member-place-list owner-booking table-responsive">
               <thead>
               <tr>
                  <th class="table-width-200">Event Name</th>
                  <th>Event Type</th>
                  <th>Role</th>
                  <th>Status</th>
                  <th>Action</th>
               </tr>
               </thead>
               <tbody>
                  @foreach($roles as $role)
                     @include('partials.delete-event-role')
                     <tr>
                        <td data-title="Title" class="table-width-200">
                           <a href="{{ route('events.show', $role->event->id) }}" style="cursor: pointer">
                              {{ $role->event->event_name }}
                           </a>
                        </td>
                        <td data-title="Type">{{ $role->event->event_type }}</td>
                        <td data-title="Role">{{ $role->role->name }}</td>
                        @if ($role->event->getEventStatus() == 'Active')
                           <td data-title="Status" class="active">{{ $role->event->getEventStatus() }}</td>
                        @elseif ($role->event->getEventStatus() == 'Live')
                           <td data-title="Status" class="approved">{{ $role->event->getEventStatus() }}</td>
                        @elseif ($role->event->getEventStatus() == 'Past')
                           <td data-title="Status" class="cancel">{{ $role->event->getEventStatus() }}</td>
                        @endif
                        <td>
                           <a href="{{ route('events.show', $role->event->id) }}" class="view" title="View"><i class="la la-eye"></i></a>
                           <i class="la la-trash" title="Delete" data-bs-toggle="modal" data-bs-target="#delete-event-role-{{ $role->id }}"></i>
                        </td>
                     </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
@endsection
