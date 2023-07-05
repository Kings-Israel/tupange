<div>
   <main id="main" class="site-main">
      <div class="site-content owner-content">
         <div class="container">
               <div class="member-place-wrap">
                  <div class="member-wrap-top mt-2">
                     <h2>{{ $event->event_name }}'s Guest List</h2>
                     <div>
                        <a class="btn m-2" href="{{ route('events.show', $event) }}">Back to My Event</a>
                        {{-- <i class="fas fa-upload" style="font-size: 22px" title="Upload Guest List" data-bs-toggle="modal" data-bs-target="#upload-guests-{{ $event->id }}" style="font-size: 18px"></i> --}}
                        {{-- @if ($guests->count())
                           <a href="{{ route('client.event.guest.download', $event) }}">
                              <i class="fas fa-save" title="Download Guest List" style="font-size: 18px"></i>
                           </a>
                           <i title="Send All Invites" wire:click.prevent="sendAllInvites({{$event}})" class="fa fa-paper-plane" style="cursor: pointer"></i>
                           <button class="btn submit-mark-as-attended">Mark Selected as Attended</button>
                        @endif --}}
                        {{-- <button class="btn m-2" data-bs-toggle="modal" data-bs-target="#add-guest-{{ $event->id }}">Add Guest</button> --}}
                        {{-- <a href="{{ route('client.event.guest.add.form', $event->id) }}" class="btn m-2">Add Guest</a> --}}
                        {{-- @include('partials.add-guest') --}}
                     </div>
                  </div><!-- .member-place-wrap --><div class="member-wrap event-show">
                  <div class="member-statistical">
                     <div class="row">
                        <div class="col-lg-3 col-6 mb-1">
                           <div class="item green">
                              <h3>Attended Guests</h3>
                              <span class="number">{{ $this->getAttendedGuestsCount()->event_guests_count }}</span>
                              <span class="line"></span>
                           </div>
                        </div>
                        <div class="col-lg-3 col-6 mb-1">
                           <div class="item green">
                              <h3>Default</h3>
                              <span class="number">{{ $this->getDefaultGuestsCount()->event_guests_count }}</span>
                              <span class="line"></span>
                           </div>
                        </div>
                        <div class="col-lg-3 col-6 mb-1">
                           <div class="item green">
                              <h3>Invited Guests</h3>
                              <span class="number">{{ $this->getInvitedGuestsCount()->event_guests_count }}</span>
                              <span class="line"></span>
                           </div>
                        </div>
                        <div class="col-lg-3 col-6 mb-1">
                           <div class="item green">
                              <h3>Confirmed Guests</h3>
                              <span class="number">{{ $this->getConfirmedGuestsCount()->event_guests_count }}</span>
                              <span class="line"></span>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-lg-3 col-md-6 col-sm-12">
                     @if ($this->getPaidTicketsCount()->event_guests_count > 0)
                        <div class="d-flex flex-column">
                           <span>Tickets Paid: <strong>{{ $this->getPaidTicketsCount()->event_guests_count }}</strong></span>
                           <span>Tickets Paid Amount: <strong>Ksh.{{ number_format($this->getPaidTicketsAmount()) }}</strong></span>
                        </div>
                     @endif
                     {{-- <label for="" class="form-label">Search name, email or phone number</label>
                     <input wire:model="search" type="phone_number" name="s" class="form-control"> --}}
                  </div>
                  <div class="col-lg-9 col-md-12 col-sm-12">
                     <div class="guests-action-btns" style="float: right">
                        <span class="badge" title="Upload Guest List" data-bs-toggle="modal" data-bs-target="#upload-guests-{{ $event->id }}">
                           <i class="fas fa-upload"></i>Upload Guests
                        </span>
                        @include('partials.upload-guests')
                        <a href="{{ route('client.event.guest.add.form', $event->id) }}" class="btn m-2">Add Guest</a>
                        <a href="{{ route('client.event.tickets', $event) }}" class="btn btn-info m-2" style="background: #1DA1F2;">
                           Manage Tickets
                        </a>
                        @if ($guests->count())
                           <a href="#" title="Send All Invites" id="send-all-invites" wire:click.prevent="sendAllInvites({{$event}})" class="btn m-2" style="cursor: pointer; background: #212121;">Send All Invites</a>
                           <a href="#" title="Send All Invites" id="send-selected-invites" class="btn m-2" style="cursor: pointer; background: #212121;" hidden>Send Selected Invites</a>
                           <a href="{{ route('client.event.guest.download', $event) }}" class="m-2">
                              <i class="fas fa-save" title="Download Guest List" style="font-size: 22px"></i>
                           </a>
                           {{-- <i title="Send All Invites" wire:click.prevent="sendAllInvites({{$event}})" class="fa fa-paper-plane" style="cursor: pointer; font-size: 20px"></i> --}}
                           {{-- <button class="btn submit-mark-as-attended">Mark Selected as Attended</button> --}}
                        @endif
                     </div>
                  </div>
               </div>
                  <table class="member-place-list owner-booking table-responsive">
                     <thead>
                           <tr>
                              <th>#</th>
                              <th class="table-width-100">Name</th>
                              <th class="table-width-100">Email</th>
                              <th class="table-width-100">Status</th>
                              <th class="table-width-150">Ticket</th>
                              <th class="table-width-50">Paid</th>
                              @if ($event->isCorporate)
                              <th>Company</th>
                              @endif
                              <th class="table-width-100">Role</th>
                              <th>Added On</th>
                              <th>Actions</th>
                           </tr>
                     </thead>
                     <tbody>
                        <form action="{{ route('client.event.guest.invite.send') }}" class="form" method="POST" id="send-invites-form">
                           @foreach ($guests as $guest)
                              <tr @if($guest->ticketSent) style='background-color: #f0f0f0' @endif>
                                 @include('partials.event-ticket')
                                 <td>
                                    <input type="checkbox" name="select_guest[]" id="select-guest-{{ $guest->id }}" value="{{ $guest->id }}" onchange="selectedGuest(this)">
                                 </td>
                                 <td class="table-width-100" data-title="Name">
                                    <span>{{ $guest->first_name }} {{ $guest->last_name }}</span>
                                 </td>
                                 <td class="table-width-100" data-title="Email">
                                    <p>{{ $guest->email}}</p>
                                 </td>
                                 <td class="table-width-100" data-title="Status">
                                    {{ $guest->status }}
                                 </td>
                                 <td class="table-width-150">
                                    {{ $guest->ticket_title }}(Ksh.{{ $guest->ticket_price }})
                                 </td>
                                 <td class="table-width-50" data-title="Phone Number">
                                    <p>{{ $guest->is_paid ? 'Yes' : 'No'}}</p>
                                 </td>
                                 @if ($event->isCorporate)
                                    <td data-title="Company">
                                       {{ $guest->company }}
                                    </td>
                                 @endif
                                 <td class="table-width-100" data-title="Role">
                                    {{ $guest->role ? $guest->role->role : 'No Role' }}
                                 </td>
                                 <td data-title="Added On" class="table-width-150">
                                       {{ $guest->created_at->format('M d, Y') }}
                                 </td>
                                 <td class="table-width-150">
                                    <span class="actions-bubble">
                                       @if (!$guest->ticketSent)
                                          <i data-title="Send Invite" wire:click.prevent="sendInvite({{ $guest }})" class="fa fa-paper-plane" style="font-size: 18px"></i>
                                       @else
                                          <i class="las la-check" title="Event invite sent to guest" style="color: green;"></i>
                                       @endif
                                       <div x-data="{ isOpen: false }">
                                          <i class="fa fa-angle-down" @click="isOpen = !isOpen"></i>
                                          <div
                                             x-cloak
                                             x-show="isOpen"
                                             @click.away="isOpen = false"
                                             @keydown.escape.window = "isOpen = false"
                                             class="search-results"
                                          >
                                             <ul>
                                                <li class="result" style="padding: 5px;cursor: pointer;" @click="isOpen = false" wire:click="deleteGuest({{ $guest->id }})">
                                                   <i class="la la-trash-alt"></i>Delete
                                                </li>
                                                {{-- <li class="result" style="padding: 5px;cursor: pointer" @click="isOpen = false" data-bs-toggle="modal" data-bs-target="#edit-guest-{{ $guest->id }}">
                                                   <i class="las la-edit"></i>Edit Guest
                                                </li> --}}
                                                @if (!$guest->is_paid)
                                                   <li class="result" style="padding: 5px;cursor: pointer;" @click="isOpen = false">
                                                      <a href="{{ route('client.event.guest.edit.form', ['event_id' => $guest->event_id, 'guest_id' => $guest->id]) }}" style="color: gray">
                                                         <i class="las la-edit"></i>Edit Guest
                                                      </a>
                                                   </li>
                                                @endif
                                                <li class="result" style="padding: 5px;cursor: pointer;" @click="isOpen = false" data-bs-toggle="modal" data-bs-target="#get-ticket-{{ $guest->id }}">
                                                   <i class="la la-eye"></i>View Ticket
                                                </li>
                                             </ul>
                                          </div>
                                       </div>
                                    </span>
                                 </td>
                              </tr>
                           @endforeach
                           @csrf
                        </form>
                     </tbody>
                  </table>
               </div><!-- .member-wrap-top -->
               <div wire:loading>
               <x-loader></x-loader>
            </div>
         </div>
      </div><!-- .site-content -->
   </main><!-- .site-main -->
   @push('scripts')
      <script>
         function useProvidedContact() {
            var result = document.querySelector('input[name="use_provided_guest_contacts"]:checked');
            if(result){
               document.getElementById("invitation_phone_number").value = document.getElementById('phone_number').value;
               document.getElementById("invitation_email").value = document.getElementById('email').value;
            }
            else{
               document.getElementById("invitation_phone_number").value = ''
               document.getElementById("invitation_email").value = ''
            }
         }
      </script>
   @endpush
</div>
