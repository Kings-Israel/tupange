@extends('layouts.master')
@section('title', 'Edit Program')
@section('css')
   <style>
      @media only screen and (max-width: 768px) {
         .edit-program-form {
            margin-top: 80px;
         }
      }
   </style>
@endsection
@section('content')
<div class="container">
<br>
   <div class="row">
   <div class="col-lg-1"></div>
   <div class="col-lg-10">
      <div class="edit-program-form">
         <div class="row mb-3">
            <div class="col-lg-6 col-md-6 col-sm-12">
               <h1>Edit Program Details</h1>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
               <a href="{{ route('client.programs.index') }}" class="link-to-dashboard" style="float:right">
                  <button class="btn btn-info" style="background: #1DA1F2">Back To My Programs</button>
               </a>
            </div>
         </div>
         <form class="form" action="{{ route('client.program.update', $eventProgram->id) }}" method="POST" id="add_program" enctype="multipart/form-data">
               @csrf
               <input type="hidden" name="eventProgramId" value="{{ $eventProgram->id }}">
               <p id="venue_lat" hidden>{{ $eventProgram->venue_location_lat }}</p>
               <p id="venue_long" hidden>{{ $eventProgram->venue_location_long }}</p>
               <div class="field-group">
                  <div class="row">
                     <div class="col-md-6">
                           <div class="field-input">
                              <label for="event_name">Event Name<span class="required">*</span></label>
                              <input type="text" placeholder="Enter your event name" name="event_name" id="event_name" value="{{ $eventProgram->event_name }}" onchange="clearError(this)">
                              <span id="event_name-Error"><strong style="color: red"></strong></span>
                           </div>
                     </div>
                     <div class="col-md-6">
                        <div class="field-group field-input">
                           <label for="company_name">Event Type <span class="required">*</span>@if (!collect($eventTypes)->contains($eventProgram->event_type))(Custom)@endif</label>
                           {{-- <select name="event_type" id="event_type" class="form-control" onchange="clearError(this)">
                              <option value="" disabled>Select Event Type</option>
                              <option value="Birthday" {{ $eventProgram->event_type == 'Birthday' ? 'selected' : '' }}>Birthday</option>
                           </select>
                           <span id="event_type-Error"><strong style="color: red"></strong></span> --}}
                           @if (collect($eventTypes)->contains($eventProgram->event_type))
                              <select name="event_type" id="event_type" class="form-control">
                                 @foreach ($eventTypes as $type)
                                    <option value="{{ $type }}" {{ $eventProgram->event_type == $type ? 'selected' : '' }}>{{ $type }}</option>
                                 @endforeach
                              </select>
                              <span id="event_type-Error">
                                 <strong class="error-message"></strong>
                              </span>
                           @else
                              <div class=" field-group field-input" id="custom-event-input">
                                 <input type="text" placeholder="Enter event type" name="event_type" id="event_type" value="{{ $eventProgram->event_type }}">
                              </div>
                              <span id="event_type-Error">
                                 <strong class="error-message"></strong>
                              </span>
                           @endif
                           <div class=" field-group field-input mt-1" id="custom-event-type-input" hidden>
                              <label for="event_type">Custom Event Type</label>
                              <input type="text" placeholder="Enter event type" name="custom_event_type" value="">
                           </div>
                           <span id="custom_event_type-Error">
                              <strong class="error-message"></strong>
                           </span>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="field-input">
                           <label for="email">Event Starts <span class="required">*</span></label>
                           <input type="date" placeholder="DD/MM/YYYY"  name="event_start_date" id="event_start_date" value="{{ $eventProgram->start_date->format('Y-m-d') }}" onchange="clearError(this)">
                           <span id="event_start_date-Error"><strong style="color: red"></strong></span>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="field-input">
                           <label for="email">Time<span class="required">*</span></label>
                           <input type="time" name="event_start_time" id="event_start_time" value="{{ $eventProgram->start_date->format('H:i') }}" onchange="clearError(this)">
                           <span id="event_start_time-Error"><strong style="color: red"></strong></span>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="field-input">
                           <label for="email">Event Ends <span class="required">*</span></label>
                           <input type="date" placeholder="DD/MM/YYYY" name="event_end_date" id="event_end_date" value="{{ $eventProgram->end_date->format('Y-m-d') }}" onchange="clearError(this)">
                           <span id="event_end_date-Error"><strong style="color: red"></strong></span>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="field-input">
                           <label for="start time">Time<span class="required">*</span></label>
                           <input type="time" name="event_end_time" id="event_end_time" value="{{ $eventProgram->end_date->format('H:i') }}" onchange="clearError(this)">
                           <span id="event_end_time-Error"><strong style="color: red"></strong></span>
                        </div>
                     </div>
                     <div class="col-md-12">
                        <div class="field-input">
                           <label for="email">Direction Description</label>
                           <input type="text"  placeholder="Describe the direction to get to the location of the event" name="direction_description" id="direction_description" value="{{ $eventProgram->direction_description }}" onchange="clearError(this)">
                           <span id="direction_description-Error"><strong style="color: red"></strong></span>
                        </div>
                     </div>
                     <div class="listing-box" id="location">
                        <h3>Location</h3>
                        <div class="field-group field-maps">
                           <div class="field-inline">
                              <label for="pac-input">Place Location at Google Map</label>
                           </div>
                           <div class="field-map">
                              <input id="place_id" type="hidden" name="place_id">
                              <input type="text" name="event_location" id="pac-input" value="{{ $eventProgram->venue_location }}">
                              <div id="location-map"></div>
                              <span id="event_locationError">
                                 <strong class="error-message"></strong>
                              </span>
                           </div>
                        </div>
                        {{-- <div class="field-group field-maps">
                           <div class="field-inline">
                              <label for="pac-input">Place Location at Google Map</label>
                           </div>
                           <div class="field-map">
                              <input id="pac-input" class="address-input" type="text" placeholder="Search Place">
                              <div id="location-map"></div>
                              <input type="hidden" name="event_location" id="event_location">
                              <span id="event_location-Error"><strong style="color: red"></strong></span>
                           </div>
                        </div> --}}
                     </div><!-- .listing-box -->
                  </div>
               </div>
               <div class="row">
                  <div class="col-sm-6 col-md-6">
                     <h2>Program Activities</h2>
                  </div>
                  <div class="col-sm-6 col-md-6">
                     <div class="right-header__button btn btn-sm" id="program-add-activity" style="float: right">
                        <span>Add Activity</span>
                     </div>
                  </div>
               </div>
               <div id="activities">
                  @forelse ($eventProgram->eventProgramActivities as $index => $activity)
                     <div class="field-group row">
                        <div class="col-sm-3 col-md-3 col-lg-3">
                           <div class="field-input">
                              <label for="activity">Activity #{{ $index + 1 }}<span class="required">*</span></label>
                              <input type="text" placeholder="Enter activity name" name="activity[{{ $index }}]" id="activity" value="{{ $activity->activity }}" onchange="clearError(this)">
                              <span id="activity-{{ $index }}-Error"><strong style="color: red"></strong></span>
                           </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-3">
                           <div class="field-input">
                              <label for="action">Action<span class="required">*</span></label>
                              <input type="text" placeholder="Enter action" name="action[{{ $index }}]" id="action" value="{{ $activity->actions }}" onchange="clearError(this)">
                              <span id="action-{{ $index }}-Error"><strong style="color: red"></strong></span>
                           </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-3">
                           <div class="field-input">
                              <label for="start time">Start Time<span class="required">*</span></label>
                              <input type="time" name="activity_start_time[{{ $index }}]" id="activity_start_time" value="{{ $activity->start_time->format('H:i') }}" onchange="clearError(this)">
                              <span id="activity_start_time-{{ $index }}-Error"><strong style="color: red"></strong></span>
                           </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-2">
                           <div class="field-input">
                              <label for="activity_end_time">End Time<span class="required">*</span></label>
                              <input type="time" name="activity_end_time[{{ $index }}]" id="activity_end_time" value="{{ $activity->end_time->format('H:i') }}" onchange="clearError(this)">
                              <span id="activity_end_time-{{ $index }}-Error"><strong style="color: red"></strong></span>
                           </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-1 program-activity-delete" style="margin-top: 20px;">
                           <i class="fas fa-trash"></i>
                        </div>
                     </div>
                  @empty
                     <div class="field-group row">
                        <div class="col-sm-3 col-md-3 col-lg-3">
                           <div class="field-input">
                              <label for="activity">Activity #1<span class="required">*</span></label>
                              <input type="text" placeholder="Enter activity name" name="activity[0]" id="activity" value="{{ old('activity[0]') }}" onchange="clearError(this)">
                              <span id="activity-0-Error"><strong style="color: red"></strong></span>
                           </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-3">
                           <div class="field-input">
                              <label for="action">Action<span class="required">*</span></label>
                              <input type="text" placeholder="Enter action" name="action[0]" id="action" value="{{ old('action[0]') }}" onchange="clearError(this)">
                              <span id="action-0-Error"><strong style="color: red"></strong></span>
                           </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-3">
                           <div class="field-input">
                              <label for="start time">Start Time<span class="required">*</span></label>
                              <input type="time" name="activity_start_time[0]" id="activity_start_time" value="{{ old('activity_start_time[0]') }}" onchange="clearError(this)">
                              <span id="activity_start_time-0-Error"><strong style="color: red"></strong></span>
                           </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-2">
                           <div class="field-input">
                              <label for="activity_end_time">End Time<span class="required">*</span></label>
                              <input type="time" name="activity_end_time[0]" id="activity_end_time" value="{{ old('activity_end_time[0]') }}" onchange="clearError(this)">
                              <span id="activity_end_time-0-Error"><strong style="color: red"></strong></span>
                           </div>
                        </div>
                        <div class="col-sm-12 col-md-3 col-lg-1 program-activity-delete" style="margin-top: 20px;">
                           <i class="fas fa-trash"></i>
                        </div>
                     </div>
                  @endforelse
               </div>
               <div class="row">
                  <div class="col-sm-6 col-md-6">
                     <h3>Contacts</h3>
                  </div>
                  <div class="col-sm-6 col-md-6">
                     <div class="right-header__button btn btn-sm" id="program-add-contact" style="float: right">
                        <span>Add Contact</span>
                     </div>
                  </div>
               </div>
               <div id="contacts">
                  @if ($eventProgram->contacts != null)
                     @for ($i = 0; $i < count($eventProgram->contacts); $i++)
                        <div class="field-group row">
                           <div class="col-sm-12 col-md-6">
                              <div class="field-input">
                                 <label for="program_contact_name">Name<span class="required">*</span></label>
                                 <input type="text" placeholder="Enter Contact's Name" name="program_contact_name[{{ $i }}]" value="{{ key($eventProgram->contacts[$i]) }}" id="program_contact_name" onchange="clearError(this)">
                                 <span id="program_contact_name-{{ $i }}-Error"><strong style="color: red"></strong></span>
                              </div>
                           </div>
                           <div class="col-sm-12 col-md-5">
                              <div class="field-input">
                                 <label for="program_contact_phone">Phone Number<span class="required">*</span></label>
                                 <input type="number" placeholder="Enter Phone Number" name="program_contact_phone[{{ $i }}]" value="{{ $eventProgram->contacts[$i][key($eventProgram->contacts[$i])] }}" id="program_contact_phone" onchange="clearError(this)">
                                 <span id="program_contact_phone-{{ $i }}-Error"><strong style="color: red"></strong></span>
                              </div>
                           </div>
                           <div class="col-sm-12 col-md-1 program-contact-delete" style="margin-top: 20px; float: right;">
                              <i class="fas fa-trash"></i>
                           </div>
                        </div>
                     @endfor
                  @else
                     <div class="field-group row">
                        <div class="col-sm-12 col-md-6">
                           <div class="field-input">
                              <label for="program_contact_name">Name<span class="required">*</span></label>
                              <input type="text" placeholder="Enter Contact's Name" name="program_contact_name[0]" id="program_contact_name" onchange="clearError(this)">
                              <span id="program_contact_name-0-Error"><strong style="color: red"></strong></span>
                           </div>
                        </div>
                        <div class="col-sm-12 col-md-5">
                           <div class="field-input">
                              <label for="program_contact_phone">Phone Number<span class="required">*</span></label>
                              <input type="number" placeholder="Enter Phone Number" name="program_contact_phone[0]" id="program_contact_phone" onchange="clearError(this)">
                              <span id="program_contact_phone-0-Error"><strong style="color: red"></strong></span>
                           </div>
                        </div>
                        <div class="col-sm-12 col-md-1 program-contact-delete" style="margin-top: 20px; float: right;">
                           <i class="fas fa-trash"></i>
                        </div>
                     </div>
                  @endif
               </div>
               <div class="field-submit d-flex justify-content-between">
                  <a href="{{ route('client.programs.index') }}" class="btn" style="background: red">Cancel</a>
                  <input type="submit" value="Update" id="add-event" class="btn">
               </div>
         </form><!-- .billingForm -->
         <br>
      </div><!-- .checkout-form -->
   </div>
   <div class="col-lg-1"></div>
</div>
@push('scripts')
<script>
   let i = 0;
   let acitivities_count = {!! json_encode($eventProgram->eventProgramActivities->count()) !!}
   let k = acitivities_count;
   var mapInstance;
   var marker;

   let lat = document.getElementById('venue_lat').innerHTML
   let long = document.getElementById('venue_long').innerHTML

   var mapInstance;
   var marker;

   function placeMarker(location) {
      if (marker) {
         marker.setPosition(location);
      } else {
         marker = new google.maps.Marker({
               position: location,
               map: mapInstance
         });
      }
   }

   // function initMap() {
   //    var latlng = new google.maps.LatLng(lat, long);
   //    var mapOptions = {
   //          zoom: 15,
   //          center: latlng,
   //          mapTypeId: google.maps.MapTypeId.ROADMAP,
   //          mapTypeControlOptions: {
   //             style: google.maps.MapTypeControlStyle.DEFAULT
   //          }
   //    };

   //    var defaultBounds = new google.maps.LatLngBounds(
   //       new google.maps.LatLng(-1.270104, 36.80814));

   //    var input = document.getElementById('pac-input');

   //    var searchBox = new google.maps.places.SearchBox(input, {
   //       bounds: defaultBounds
   //    });

   //    searchBox.addListener('places_changed', function() {
   //       var places = searchBox.getPlaces();

   //       if (places.length == 0) {
   //             return;
   //       }

   //       // Clear out the old markers.
   //       markers.forEach(function(marker) {
   //             marker.setMap(null);
   //       });
   //       markers = [];

   //       // For each place, get the icon, name and location.
   //       var bounds = new google.maps.LatLngBounds();
   //       places.forEach(function(place) {
   //             if (!place.geometry) {
   //                console.log("Returned place contains no geometry");
   //                return;
   //             }
   //          var icon = {
   //                url: place.icon,
   //                size: new google.maps.Size(71, 71),
   //                origin: new google.maps.Point(0, 0),
   //                anchor: new google.maps.Point(17, 34),
   //                scaledSize: new google.maps.Size(25, 25)
   //          };

   //             // Create a marker for each place.
   //             markers.push(new google.maps.Marker({
   //                map: map,
   //                icon: icon,
   //                title: place.name,
   //                position: place.geometry.location
   //             }));

   //             if (place.geometry.viewport) {
   //                // Only geocodes have viewport.
   //                bounds.union(place.geometry.viewport);
   //             } else {
   //                bounds.extend(place.geometry.location);
   //             }
   //       });
   //       map.fitBounds(bounds);
   //    });
   //    mapInstance = new google.maps.Map(document.getElementById("location-map"), mapOptions);

   //    marker = new google.maps.Marker({
   //          position: latlng,
   //          map: mapInstance,
   //          draggable: false
   //      })


   //    google.maps.event.addListener(mapInstance, 'click', function(event) {
   //       placeMarker(event.latLng);
   //    });
   // }
   function initMap() {
      var latLng = new google.maps.LatLng(lat, long);
      var map = new google.maps.Map(document.getElementById('location-map'), {
         center: {lat: parseFloat(lat), lng: parseFloat(long)},
         zoom: 13
      });

      var input = document.getElementById('pac-input');

      var autocomplete = new google.maps.places.Autocomplete(input);
      autocomplete.bindTo('bounds', map);

      var infowindow = new google.maps.InfoWindow();

      marker = new google.maps.Marker({
         map: map,
         anchorPoint: new google.maps.Point(0, -29),
         position: latLng,
      });

      autocomplete.addListener('place_changed', function() {
         infowindow.close();
         marker.setVisible(false);
         var place = autocomplete.getPlace();
         if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
         }

         document.getElementById('place_id').value = place.place_id

         map.setCenter(place.geometry.location);
         map.setZoom(17);

         placeMarker(place.geometry.location);
         marker.setVisible(true);
      });

      geocoder = new google.maps.Geocoder;

      google.maps.event.addListener(map, 'click', function(event) {
         geocoder.geocode({
            'location': event.latLng
         }, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
               if (results[0]) {
                  document.getElementById('place_id').value = results[0].place_id
               } else {
                  console.log('No results found');
               }
            } else {
               console.log('Geocoder failed due to: ' + status);
            }
         });

         placeMarker(event.latLng);
      });
   }

   var today = new Date();
   var dd = today.getDate();
   var mm = today.getMonth()+1; //January is 0 so need to add 1 to make it 1!
   var yyyy = today.getFullYear();
   if(dd<10){
   dd='0'+dd
   }
   if(mm<10){
   mm='0'+mm
   }

   today = yyyy+'-'+mm+'-'+dd;

   let activities = $('#activities')

   $(document.body).on('click', '#program-add-activity', function(e) {
      i++
      e.preventDefault()
      let add_activity_html = '<div class="field-group row" id="activities">'
         add_activity_html += '<div class="col-sm-3 col-md-3 col-lg-3">'
         add_activity_html += '<div class="field-input">'
         add_activity_html += '<label for="activity">Activity #'+ ++k +'<span class="required">*</span></label>'
         add_activity_html += '<input type="text" placeholder="Enter activity name" name="activity['+i+']" id="activity" required onchange="clearError(this)">'
         add_activity_html += '<span id="activity-'+i+'-Error"><strong style="color: red"></strong></span>'
         add_activity_html += '</div>'
         add_activity_html += '</div>'
         add_activity_html += '<div class="col-sm-12 col-md-3 col-lg-3">'
         add_activity_html += '<div class="field-input">'
         add_activity_html += '<label for="action">Action<span class="required">*</span></label>'
         add_activity_html += '<input type="text" placeholder="Enter action" name="action['+i+']" id="action" required onchange="clearError(this)">'
         add_activity_html += '<span id="action-'+i+'-Error"><strong style="color: red"></strong></span>'
         add_activity_html += '</div>'
         add_activity_html += '</div>'
         add_activity_html += '<div class="col-sm-12 col-md-3 col-lg-3">'
         add_activity_html += '<div class="field-input">'
         add_activity_html += '<label for="activity_start_time">Start Time<span class="required">*</span></label>'
         add_activity_html += '<input type="time" name="activity_start_time['+i+']" id="activity_start_time" required onchange="clearError(this)">'
         add_activity_html += '<span id="activity_start_time-'+i+'-Error"><strong style="color: red"></strong></span>'
         add_activity_html += '</div>'
         add_activity_html += '</div>'
         add_activity_html += '<div class="col-sm-12 col-md-3 col-lg-2">'
         add_activity_html += '<div class="field-input">'
         add_activity_html += '<label for="activity_end_time">End Time<span class="required">*</span></label>'
         add_activity_html += '<input type="time" name="activity_end_time['+i+']" id="activity_end_time" required onchange="clearError(this)">'
         add_activity_html += '<span id="activity_end_time-'+i+'-Error"><strong style="color: red"></strong></span>'
         add_activity_html += '</div>'
         add_activity_html += '</div>'
         add_activity_html += '<div class="col-sm-12 col-md-3 col-lg-1 program-activity-delete" style="margin-top: 20px;">'
         add_activity_html += '<i class="fas fa-trash"></i>'
         add_activity_html += '</div>'
         add_activity_html += '</div>'

      $(add_activity_html).appendTo(activities)
   })

   let contacts = $('#contacts')

   $(document.body).on('click', '#program-add-contact', function(e) {
      i++
      e.preventDefault()
      let add_contact_html = '<div class="field-group row">'
         add_contact_html += '<div class="col-sm-12 col-md-6">'
         add_contact_html += '<div class="field-input">'
         add_contact_html += '<label for="program_contact_name">Name<span class="required">*</span></label>'
         add_contact_html += '<input type="text" placeholder="Enter Contact\'s Name" name="program_contact_name['+i+']" id="program_contact_name" required onchange="clearError(this)">'
         add_contact_html += '<span id="program_contact_name-'+i+'-Error"><strong style="color: red"></strong></span>'
         add_contact_html += '</div>'
         add_contact_html += '</div>'
         add_contact_html += '<div class="col-sm-12 col-md-5">'
         add_contact_html += '<div class="field-input">'
         add_contact_html += '<label for="program_contact_phone">Phone Number<span class="required">*</span></label>'
         add_contact_html += '<input type="number" placeholder="Enter Phone Number" name="program_contact_phone['+i+']" id="program_contact_phone" required onchange="clearError(this)">'
         add_contact_html += '<span id="program_contact_phone-'+i+'-Error"><strong style="color: red"></strong></span>'
         add_contact_html += '</div>'
         add_contact_html += '</div>'
         add_contact_html += '<div class="col-sm-12 col-md-1 program-contact-delete" style="margin-top: 20px; float: right;">'
         add_contact_html += '<i class="fas fa-trash"></i>'
         add_contact_html += '</div>'
         add_contact_html += '</div>'

      $(add_contact_html).appendTo(contacts)
   })

   $(document.body).on("click", ".program-activity-delete", function(event){
      let $btn = $(this);
      let $item = $btn.parent();
      if (activities.children().length > 1) $item.remove();
   });

   $(document.body).on("click", ".program-contact-delete", function(event){
      let $btn = $(this);
      let $item = $btn.parent();
      if (contacts.children().length > 1) $item.remove();
   });

   $('#event_start_date').attr('min', today)
   $('#event_end_date').attr('min', today)

   $('#event_start_date').on('change', function () {
      let min_end_date = $(this).val()
      $('#event_end_date').attr('min', min_end_date)
   })

   $('#event_start_time').on('change', function () {
      let min_end_time = $(this).val()
      $('#event_end_time').val(min_end_time)
   })

   $('#event_guests_expected').on('change', function() {
      let min_guests = $(this).val()
      $('#event_guests_max').attr('min', min_guests)
   })

   function clearError(element){
      $(element).parent().children('span').text()
   }

   $('#event_type').on('change', function() {
      if($(this).val() == 'Other') {
         $('#custom-event-type-input').removeAttr('hidden')
      } else {
         $('#custom-event-type-input').attr('hidden', 'hidden')
      }
   })

   $("#add-event").on("click", function(e) {
      // Prevent normal submit action
      e.preventDefault();

      // Collect current latlng of marker and put in hidden form field
      if (marker) {
         $("#event_location").val(marker.getPosition());
      } else {
         alert('Please select the location')
         return
      }

      // $("#add_program").submit();

      let formData = $('#add_program').serializeArray()

      let newKey = ''

      $.ajax({
         method: "POST",
         dataType: "json",
         headers: {
            Accept: "application/json"
         },
         url: "{{ route('client.program.update', json_encode($eventProgram->id)) }}",
         data: formData,
         success: ({ redirectPath }) => window.location.assign(redirectPath),
         error: (response) => {
            if(response.status === 422) {
               let errors = response.responseJSON;
               Object.keys(errors).forEach(function (key) {
                  if (key.includes('.')) {
                     newKey = key.replace('.', '-')
                     $("#" + newKey + "-Error").children("strong").text(errors[key][0]);
                  } else {
                     $("#" + key + "-Error").children("strong").text(errors[key][0]);
                  }
               });
            } else {
               toastr.options =
                  {
                     "closeButton" : true,
                     "progressBar" : true,
                     "positionClass" : "toast-bottom-right"
                  }
               toastr.error("An error occured. Please try again");
            }
         }
      })
   });
</script>
<script src={{ config('services.maps.key') }} async defer></script>
@endpush
</div>

@endsection

