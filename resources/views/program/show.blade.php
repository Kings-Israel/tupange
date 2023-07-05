@extends('layouts.master')
@section('title', 'Event')
@section('css')
<style>
   .member-wrap {
      margin-top: 10px;
   }
   @media only screen and (min-width: 997px) {

   }
   @media only screen and (max-width: 997px) {
      .member-wrap {
         margin-top: 140px;
      }
      .program-btn {
         width: 100%;
         margin: 5px;
      }
   }
   @media only screen and (max-width: 575px) {
      .member-wrap {
         margin-top: 90px;
      }
      #event-program-action-buttons {
         margin-top: -30px;
         margin-bottom: 10px;
      }
      .program-btn {
         width: 100%;
         margin: 5px;
      }
   }
</style>
@endsection
@section('content')
   <div class="container">
      <div class="col-md-7">
         <p id="event_lat" hidden>{{ $eventProgram->venue_location_lat }}</p>
         <p id="event_long" hidden>{{ $eventProgram->venue_location_long }}</p>
      </div>
      @include('partials.program-payment')
      <div class="member-wrap">
         <div class="owner-top-inner row">
            <div class="owner-top-info col-lg-6 col-md-6 col-sm-12">
               <div class="info">
                  <h1 class="verified">{{ $eventProgram->event_name }}</h1>
               </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12" id="event-program-action-buttons">
               <div class="row">
                  <div class="col-lg-3 col-md-6 col-sm-12">
                     <a href="{{ route('client.program.edit', $eventProgram->id) }}">
                        <button class="btn program-btn" style="background-color: #F58C1C">Edit</button>
                     </a>
                  </div>
                  <div class="col-lg-3 col-md-6 col-sm-12">
                     @if ($eventProgram->canDownload == false)
                        <span data-bs-toggle="modal" data-bs-target="#program-{{ $eventProgram->id }}">
                           <button class="btn program-btn"  style="background-color: black">Download</button>
                        </span>
                     @else
                        <a href="{{ route('client.program.pdf', $eventProgram) }}">
                           <button class="btn program-btn" style="background: #black">Download</button>
                        </a>
                     @endif
                  </div>
                  <div class="col-lg-6 col-md-12 col-sm-12">
                     <a href="{{ route('client.programs.index') }}">
                        <button class="btn btn-primary program-btn" style="background: #1DA1F2">Go to Programs Dashboard</button>
                     </a>
                  </div>
               </div>
            </div>
         </div>
         <div class="member-statistical">
            <div class="row">
               <div class="col-sm-12 col-md-12 col-lg-8">
                  <h3>{{ $eventProgram->event_type }}</h3>
                  <span>
                     Event Starts At: <strong>{{ Carbon\Carbon::parse($eventProgram->start_date)->format('H:i, d M, Y') }}</strong>
                  </span>
                  <br>
                  <span>
                     Event End At: <strong>{{ Carbon\Carbon::parse($eventProgram->end_date)->format('H:i, d M, Y') }}</strong>
                  </span>
                  <hr>
                  <h4>Event Programme</h4>
                  <table class="member-place-list owner-booking table-responsive">
                     <thead>
                        <tr>
                           <td class="table-width-100">Activity</td>
                           <td class="table-width-200">Action</td>
                           <td class="table-width-20">Starts At</td>
                           <td class="table-width-20">Ends At</td>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($eventProgram->eventProgramActivities as $activity)
                           <tr>
                              <td class="table-width-100">{{ $activity->activity }}</td>
                              <td class="table-width-200">{{ $activity->actions }}</td>
                              <td class="table-width-20">{{ $activity->start_time->format('H:i') }}</td>
                              <td class="table-width-20">{{ $activity->end_time->format('H:i') }}</td>
                           </tr>
                        @endforeach
                     </tbody>
                  </table>
                  <hr>
                  @if ($eventProgram->contacts)
                     <h4>Contact Persons</h4>
                     <table class="member-place-list owner-booking table-responsive">
                        <thead>
                           <tr>
                              <td class="table-width-150">Name</td>
                              <td>Phone Number</td>
                           </tr>
                        </thead>
                        <tbody>
                           @for ($i = 0; $i < count($eventProgram->contacts); $i++)
                              <tr>
                                 <td class="table-width-150">{{ key($eventProgram->contacts[$i]) }}</td>
                                 <td>{{ $eventProgram->contacts[$i][key($eventProgram->contacts[$i])] }}</td>
                              </tr>
                           @endfor
                        </tbody>
                     </table>
                  @endif
               </div>
               <div class="col-sm-12 col-md-12 col-lg-4">
                  <h3>Directions and Location</h3>
                  <hr>
                  <h5>Direction Description</h5>
                  <p>{{ $eventProgram->direction_description }}</p>
                  <hr>
                  <p>{{ $eventProgram->venue_location }}</p>
                  <div class="listing-box" id="location">
                     <div class="field-group field-maps">
                        <div class="field-map">
                           <div id="location-map"></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
@endsection
@push('scripts')
   <script>
      let lat = document.getElementById('event_lat').innerHTML
      let long = document.getElementById('event_long').innerHTML

      var mapInstance;
      var marker;

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

