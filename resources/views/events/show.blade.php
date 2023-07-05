@extends('layouts.master')
@section('title', 'Event')
@push('scripts')
   <script>
      var x = setInterval(() => {
         var now = new Date().getTime()

         var distance = countDownDate - now

         var days = Math.floor(distance / (1000 * 60 * 60 * 24))
         var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
         var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
         var seconds = Math.floor((distance % (1000 * 60)) / 1000);

         // Display the result in the element with id="countdown"
         document.getElementById("countdown").innerHTML = days + "d " + hours + "h "
         + minutes + "m ";

         // If the count down is finished, write some text
         var end = endDate - now
         // console.log(end);
         if (now > countDownDate && now < endDate) {
            clearInterval(x);
            document.getElementById("countdown").innerHTML = "LIVE";
         } else if (now > endDate) {
            clearInterval(x);
            document.getElementById("countdown").innerHTML = "PAST";
         }
      }, 1000);
   </script>
@endpush
@section('css')
   <style>
      .event-top-buttons {
         display: flex;
         justify-content: end;
      }
      .event-top-button {
         margin: 0 3px;
      }
      .link-to-dashboard {
         float: right;
      }

      @media only screen and (max-width: 575px) {
         .owner-top-button {
            margin-top: -25px;
            margin-bottom: 10px;
         }
         .link-to-dashboard {
            float: left;
         }
      }
   </style>
@endsection
@section('content')
   <div class="container">
      <div class="col-md-7">
         <p id="event_lat" hidden>{{ $event->event_location_lat }}</p>
         <p id="event_long" hidden>{{ $event->event_location_long }}</p>
      </div>

      <div class="member-wrap event-show">
         <div class="row">
            <div class="col-lg-7 col-md-5 col-sm-12 mt-3">
               <h1>{{ $event->event_name }}</h1>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-2 mt-3">
               <div class="owner-top-button">
                  <h2>Status: <span class="event-{{ $event->getEventStatus() }}">{{ $event->getEventStatus() }}</span></h2>
               </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-10 mt-2 mb-2">
               <a href="{{ route('events.index') }}" class="link-to-dashboard">
                  <button class="btn btn-primary">Go to My Events Dashboard</button>
               </a>
            </div>
         </div>
         <div class="event-top-buttons mb-2">
            @can('update', $event)
               <div class="event-top-button">
                  <a href="{{ route('client.event.tickets', $event) }}" class="btn btn-info" style="background: #1DA1F2">
                     Manage Tickets
                  </a>
               </div>
            @endcan
            @can('update', $event)
               <div class="event-top-button">
                  <a class="btn" style="background-color: #F58C1C" href="{{ route('events.edit', $event->id) }}">
                     Edit
                  </a>
               </div>
            @endcan
            @can('update', $event)
               <div class="event-top-button">
                  <a class="btn" style="background-color: black" href="{{ route('client.event.service.order', $event->id) }}">
                     Order Service
                  </a>
               </div>
            @endcan
            @can('viewProgram', $event)
               <div class="event-top-button">
                  @if ($event->program)
                     <a href="{{ route('client.program.show', $event->program) }}" class="btn">View Event Program</a>
                  @else
                     <a href="{{ route('client.program.create', $event->id) }}" class="btn">Create Program</a>
                  @endif
               </div>
            @endcan
         </div>
         <div class="member-statistical">
            <div class="row">
               <div class="col-lg-3 col-6 mb-1">
                  <div class="item green">
                     <h3>Countdown</h3>
                     <span class="number" id="countdown"></span>
                     <span class="line"></span>
                  </div>
               </div>
               @can('viewTasks', $event)
                  <div class="col-lg-3 col-6 mb-1">
                     <a href="{{ route('client.event.tasks', $event) }}">
                        <div class="item green">
                           <h3>Tasks</h3>
                           <span class="number">{{ $event->event_tasks->count() }}</span>
                           <span class="line"></span>
                        </div>
                     </a>
                  </div>
               @endcan
               @can('viewOrders', $event)
                  <div class="col-lg-3 col-6 mb-1">
                     <a href="{{ route('client.event.orders', $event) }}">
                        <div class="item green">
                           <h3>Event Orders</h3>
                           <span class="number">{{ $event->orders->count() }}</span>
                           <span class="line"></span>
                        </div>
                     </a>
                  </div>
               @endcan
               @can('update', $event)
                  <div class="col-lg-3 col-6 mb-1">
                     <a href="{{ route('client.event.guests', $event) }}">
                        <div class="item green">
                           <h3>Guest List and Registration</h3>
                           <span class="number">{{ $event->event_guests->count() }}</span>
                           <span class="line"></span>
                           @if ($ticket_paid_amount > 0)
                              Tickets Paid - Ksh.{{ number_format($ticket_paid_amount) }}
                           @endif
                        </div>
                     </a>
                  </div>
               @endcan
               @can('viewBudget', $event)
                  @can('updateBudget', $event)
                     <div class="col-lg-3 col-6 mb-1">
                        <a href="{{ route('client.event.budget', $event) }}">
                           <div class="item green">
                              <h3>Budget & Expenses</h3>
                              <span class="number" style="display: flex; justify-content: center">Ksh. <p>{{ number_format($budget_balance) }}</p></span>
                              <span class="line"></span>
                           </div>
                        </a>
                     </div>
                  @else
                     <div class="col-lg-3 col-6 mb-1">
                        <div class="item green">
                           <h3>Budget & Expenses</h3>
                           <span class="number" style="display: flex; justify-content: center">Ksh. <p>{{ number_format($budget_balance) }}</p></span>
                           <span class="line"></span>
                        </div>
                     </div>
                  @endcan
               @endcan
               {{-- <div class="col-lg-3 col-6 mb-1">
                  @can('update', $event)
                     <a href="{{ route('client.event.gifts', $event) }}">
                        <div class="item red">
                           <h3>Gifts Registry</h3>
                           <span class="number">{{ $event->gifts->count() }}</span>
                           <span class="line"></span>
                        </div>
                     </a>
                  @else
                     <div class="item red mb-1">
                        <h3>Gifts Registry</h3>
                        <span class="number">{{ $event->gifts->count() }}</span>
                        <span class="line"></span>
                     </div>
                  @endcan
               </div> --}}
               {{-- @can('update', $event)
                  <div class="col-lg-3 col-6 mb-1">
                     <a href="{{ route('client.event.registration', $event) }}">
                        <div class="item green">
                           <h3>Registration</h3>
                           <span class="number" style="display: flex; justify-content: center">Ksh. <p>{{ number_format($event_registration_sales) }}</p></span>
                           <span class="line"></span>
                        </div>
                     </a>
                  </div>
               @endcan --}}
               @can('update', $event)
                  <div class="col-lg-3 col-6">
                     <a href="{{ route('client.event.users', $event) }}">
                        <div class="item green">
                           <h3>Committee</h3>
                           <span class="number">{{ $event->event_users->count() + 1 }}</span>
                           <span class="line"></span>
                        </div>
                     </a>
                  </div>
               @endcan
            </div>
            <div class="row">
               <div class="col-lg-6 col-sm-12">
                  <div class="place-item layout-02 place-hover" data-maps="tartine_manufactory">
                     <div class="place-inner">
                        <div class="entry-detail">
                           <div class="place-thumb">
                              <img src="{{ $event->getEventCoverImage($event->event_poster) }}" style="margin-top: 10px; border-radius: 5px;" alt="{{ $event->event_name }}" onerror="this.onerror=null; this.src='{{ $event->user->getAvatar($event->user->avatar) }}'">
                           </div>
                           <div class="place-type list-item mt-1">
                              <span>{{ $event->event_description }}</span>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-lg-6 col-sm-12">
                  <div class="listing-box" id="location">
                     <div class="field-group field-maps">
                        <div class="field-map">
                           <div id="location-map"></div>
                        </div>
                     </div>
                     <div class="place-city mt-1">
                        <i class="la la-map-marker"></i>
                        <span>{{ $event->event_location }}</span>
                     </div>
                  </div>
               </div>
            </div>
            @can('update', $event)
               <div class="row">
                  <div class="col-6">
                     <h2>Invited Guests and Tickets</h2>
                  </div>
                  <div class="col-6">
                     <div class="event-top-button" style="float: right;">
                        <a href="{{ route('client.event.tickets', $event) }}" class="btn btn-info" style="background: #1DA1F2">
                           Manage Tickets
                        </a>
                     </div>
                  </div>
               </div>
               <div class="place-item layout-02 place-hover" data-maps="tartine_manufactory">
                  <table class="member-place-list owner-booking table-responsive">
                     <thead>
                        <tr>
                           <th class="table-width-150">Ticket Title</th>
                           <th>Ticket Price</th>
                           <th>Guest Limit</th>
                           <th class="table-width-150">Invited Guests No.</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr style="background: gray; color: white">
                           <td class="table-width-150">
                              General Admission
                           </td>
                           <td>
                              <span>Ksh.0</span>
                           </td>
                           <td>No Limit</td>
                           <td class="table-width-150">
                              {{ $event->getGeneralAdmissionGuestCount() }}
                           </td>
                        </tr>
                        @foreach ($event->registrationTickets as $ticket)
                           <tr>
                              <td class="table-width-150">{{ $ticket->title }}</td>
                              <td>Ksh. {{ number_format($ticket->price) }}</td>
                              <td>{{ $ticket->guest_limit }}</td>
                              <td class="table-width-150">{{ $ticket->getInvitedGuestsCount() }}</td>
                           </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            @endcan
         </div>
      </div>
   </div>
@endsection
@push('scripts')
   <script>
      let event_number = $('[id=event_number_view]')
      event_number.each((ind, obj) => {
         var num = obj.innerHTML.replace(/,/gi, "");
         var num2 = num.split(/(?=(?:\d{3})+$)/).join(",")
         obj.innerHTML = num2
      });
      let lat = document.getElementById('event_lat').innerHTML
      let long = document.getElementById('event_long').innerHTML

      var mapInstance;
      var marker;
      var countDownDate = new Date({!! json_encode($event->event_start_date) !!}).getTime()
      var endDate = new Date({!! json_encode($event->event_end_date) !!}).getTime()


      function initMap() {
         var latlng = new google.maps.LatLng(lat, long);
         var mapOptions = {
               zoom: 15,
               center: latlng,
               scrollwheel: false,
               zoomControl: true,
               navigationControl: true,
               mapTypeControl: false,
               scaleControl: true,
               draggable: true,
               mapTypeId: google.maps.MapTypeId.ROADMAP,
         };
         mapInstance = new google.maps.Map(document.getElementById("location-map"), mapOptions);
         marker = new google.maps.Marker({
               position: latlng,
               map: mapInstance,
               draggable: false
         })
      }
   </script>
   <script src="{{ config('services.maps.key') }}" async defer></script>
@endpush

