<div>
   <table class="member-place-list owner-booking table-responsive">
      <thead>
          <tr>
              <th class="table-width-150">Ticket</th>
              <th class="table-width-150">Price</th>
              <th class="table-width-100">Guests</th>
              <th class="table-width-150">Amount</th>
              <th class="table-width-100">Barcode</th>
              <th class="table-width-100">Phone</th>
              <th>Names</th>
              <th class="table-width-200">Actions</th>
          </tr>
      </thead>
      <tbody>
          @foreach ($registered as $guest)
              <tr>
                  @include('partials.edit-event-guest')
                  @include('partials.event-ticket')
                  <td data-title="ID" class="table-width-150">
                     <p>{{ $guest->ticket_title }}</p>
                  </td>
                  <td class="table-width-100" data-title="Ticket Price">
                     Ksh. {{ number_format($guest->ticket_price) }}
                  </td>
                  <td class="table-width-100" data-title="Guests No.">
                     <span>{{ $guest->guests }}</span>
                  </td>
                  <td class="table-width-100" data-title="Amount in Ksh.">
                     Ksh. {{ number_format($guest->amount) }}
                  </td>
                  <td class="table-width-100" data-title="Barcode">
                     {{ $guest->barcode }}
                  </td>
                  <td class="table-width-100" data-title="Phone Number">{{ $guest->phone_number }}</td>
                  <td data-title="Guest Name">
                     <span>{{ $guest->names }}</span>
                  </td>
                  <td>
                     <div class="d-flex justify-content-between table-width-200">
                        <span class="badge" data-bs-toggle="modal" data-bs-target="#get-ticket-{{ $guest->id }}" style="cursor: pointer;">
                           View Ticket
                        </span>
                        <i class="la la-edit" data-bs-toggle="modal" data-bs-target="#edit-event-guest-{{ $guest->id }}" style="cursor: pointer"></i>
                        {{-- <i class="la la-trash" wire:click="deleteGuest({{ $guest }})" style="cursor: pointer"></i> --}}
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
                                 <li class="result" style="padding: 5px;" @click="isOpen = false" wire:click="deleteGuest({{ $guest }})">
                                    <i class="la la-trash-alt"></i>Delete
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
                  </td>
              </tr>
          @endforeach
      </tbody>
   </table>
</div>
