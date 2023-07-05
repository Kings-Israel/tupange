@extends('layouts.master')
@section('title', 'Ticket Management')
@section('content')
<main id="main" class="site-main">
   <div class="site-content owner-content">
      <div class="container">
         <div class="member-place-wrap">
            <div class="member-wrap-top">
               <h2>Manage Tickets</h2>
               <div>
                  <a class="btn" href="{{ route('events.show', $event) }}">Back to My Event</a>
                  <a href="{{ route('client.event.guests', $event) }}" class="btn m-2 btn-info" style="background: #1DA1F2">Manage Guests</a>
                  <button class="btn m-2" style="background-color: #F58C1C" data-bs-toggle="modal" data-bs-target="#add-ticket-{{ $event->id }}">Add Ticket</button>
                  @include('partials.add-ticket')
               </div>
            </div><!-- .member-place-wrap -->
         </div><!-- .member-wrap-top -->
         <table class="member-place-list owner-booking table-responsive">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="table-width-150">Ticket Title</th>
                    <th class="table-width-150">Ticket Price</th>
                    <th>Guest Limit</th>
                    <th>Description</th>
                    <th>Invited Guests No.</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
               <tr style="background: gray; color: white">
                  <td data-title="ID">
                     <p></p>
                  </td>
                  <td class="table-width-150">
                     General Admission
                  </td>
                  <td class="table-width-150">
                      <span>Ksh.0</span>
                  </td>
                  <td>
                     No Limit
                  </td>
                  <td></td>
                  <td>
                     {{ $event->getGeneralAdmissionGuestCount() }}
                  </td>
                  <td></td>
               </tr>
                @foreach ($tickets as $ticket)
                    <tr>
                        @include('partials.edit-ticket')
                        @include('partials.delete-event-ticket')
                        <td data-title="ID">
                           <p>{{ $i++ }}</p>
                        </td>
                        <td class="table-width-150">
                            {{$ticket->title }}
                        </td>
                        <td class="table-width-150">
                            <span>Ksh.{{ $ticket->price }}</span>
                        </td>
                        <td>
                           {{ $ticket->guest_limit }}
                        </td>
                        <td>
                           <span>
                              {{ $ticket->description }}
                           </span>
                        </td>
                        <td>
                           <span>{{ $ticket->getInvitedGuestsCount() }}</span>
                        </td>
                        <td>
                           <i class="la la-edit" data-bs-toggle="modal" data-bs-target="#edit-ticket-{{ $ticket->id }}" style="cursor: pointer"></i>
                           <i class="la la-trash" data-bs-toggle="modal" data-bs-target="#delete-ticket-{{ $ticket->id }}" style="cursor: pointer"></i>
                           {{-- <a href="{{ route('client.event.ticket.delete', $ticket->id) }}">
                              <i class="la la-trash" style="cursor: pointer"></i>
                           </a> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
         </table>
      </div>
   </div><!-- .site-content -->
</main><!-- .site-main -->
@endsection
