
@extends('layouts.master')
@section('title', 'Create Program')
@section('css')
<style>
   .activities-example {
      background: rgb(189, 189, 189);
      padding-top: 10px;
      border-radius: 5px;
   }
   #create-program-submit {
      float: right;
   }
   @media only screen and (max-width: 768px) {
      .create-program-form {
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
      <div class="create-program-form">
         <div class="row mb-3">
            <div class="col-lg-6 col-md-6 col-sm-12">
               <h1>Program Details</h1>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12">
               <a href="{{ route('client.programs.index') }}" class="link-to-dashboard" style="float:right">
                  <button class="btn btn-info" style="background: #1DA1F2">Back To My Programs</button>
               </a>
            </div>
         </div>
         <form class="form" action="{{ route('client.program.store') }}" method="POST" id="add_program" enctype="multipart/form-data">
            <input type="hidden" name="event_id" value="{{ $event ? $event->id : null }}">
            <p id="lat" hidden>{{ $event ? $event->event_location_lat : '' }}</p>
            <p id="long" hidden>{{ $event ? $event->event_location_long : '' }}</p>
               @csrf
               <div class="field-group">
                  <div class="row">
                     <div class="col-md-6">
                        <div class="field-input">
                           <label for="event_name">Event Name<span class="required">*</span></label>
                           <input type="text" placeholder="Enter your event name" name="event_name" id="event_name" value="{{ $event ? $event->event_name : '' }}" onchange="clearError(this)">
                           <span id="event_name-Error"><strong style="color: red"></strong></span>
                        </div>
                     </div>
                     <div class="col-md-6">
                           <div class="field-group field-input">
                              <label for="company_name">Event Type<span class="required">*</span>@if ($event && !collect($eventTypes)->contains($event->event_type))(Custom)@endif</label>
                              @if ($event)
                                 @if (collect($eventTypes)->contains($event->event_type))
                                    <select name="event_type" id="event_type" class="form-control" onchange="clearError(this)">
                                       <option value="">Select Event Type</option>
                                       @foreach ($eventTypes as $eventType)
                                          <option value="{{ $eventType }}" {{ $event->event_type == $eventType ? 'selected' : '' }}>{{ $eventType }}</option>
                                       @endforeach
                                    </select>
                                 @else
                                    <div class="field-input" id="custom-event-input">
                                       <input type="text" placeholder="Enter event type" name="event_type" id="event_type" value="{{ $event->event_type }}">
                                       <span id="event_type-Error">
                                          <strong class="error-message"></strong>
                                       </span>
                                    </div>
                                 @endif
                              @else
                                 <select name="event_type" id="event_type" class="form-control">
                                    <option value="">Select Event Type</option>
                                    @foreach ($eventTypes as $eventType)
                                       <option value="{{ $eventType }}">{{ $eventType }}</option>
                                    @endforeach
                                 </select>
                                 <span id="event_type-Error">
                                    <strong class="error-message"></strong>
                                 </span>
                              @endif
                              <span id="event_type-Error"><strong style="color: red"></strong></span>
                              <div class="field-input mt-1" id="custom-event-input" hidden>
                                 <label for="event_type">Custom Event Type</label>
                                 <input type="text" placeholder="Enter event type" name="event_type_custom" id="event_type_custom" value="{{ old('event_type_custom') }}">
                                 <span id="event_type_custom-Error">
                                    <strong class="error-message"></strong>
                                 </span>
                              </div>
                           </div>
                     </div>
                     <div class="col-md-6">
                           <div class="field-input">
                              <label for="email">Event Starts <span class="required">*</span></label>
                              <input type="date" placeholder="DD/MM/YYYY"  name="event_start_date" id="event_start_date" value="{{ $event ? $event->event_start_date->format('Y-m-d') : '' }}" onchange="clearError(this)">
                              <span id="event_start_date-Error"><strong style="color: red"></strong></span>
                           </div>
                     </div>
                     <div class="col-md-6">
                           <div class="field-input">
                              <label for="email">Time<span class="required">*</span></label>
                              <input type="time" name="event_start_time" id="event_start_time" value="{{ $event ? $event->event_start_date->format('H:i') : '' }}" onchange="clearError(this)">
                              <span id="event_start_time-Error"><strong style="color: red"></strong></span>
                           </div>
                     </div>
                     <div class="col-md-6">
                           <div class="field-input">
                              <label for="email">Event Ends <span class="required">*</span></label>
                              <input type="date" placeholder="DD/MM/YYYY" name="event_end_date" id="event_end_date" value="{{ $event ? $event->event_end_date->format('Y-m-d') : '' }}" onchange="clearError(this)">
                              <span id="event_end_date-Error"><strong style="color: red"></strong></span>
                           </div>
                     </div>
                     <div class="col-md-6">
                           <div class="field-input">
                              <label for="start time">Time<span class="required">*</span></label>
                              <input type="time" name="event_end_time" id="event_end_time" value="{{ $event ? $event->event_end_date->format('H:i') : '' }}" onchange="clearError(this)">
                              <span id="event_end_time-Error"><strong style="color: red"></strong></span>
                           </div>
                     </div>
                     <div class="col-md-12">
                           <div class="field-input">
                              <label for="email">Direction Description</label>
                              <input type="text"  placeholder="Describe the direction to get to the location of the event" name="direction_description" id="direction_description" value="{{ old('direction_description') }}" onchange="clearError(this)">
                              <span id="direction_description-Error"><strong style="color: red"></strong></span>
                           </div>
                     </div>
                     <div class="listing-box" id="location">
                           <h3>Location</h3>
                           <div class="field-group field-maps">
                              <div class="field-inline">
                                 <label for="pac-input">Place Location at Google Map (Click on Map to Select*)</label>
                              </div>
                              <div class="field-map">
                                 {{-- <input id="pac-input" class="address-input" type="text" placeholder="Search Place"> --}}
                                 {{-- <input type="hidden" name="event_location" id="event_location"> --}}
                                 <input id="place_id" type="hidden" name="place_id">
                                 <input type="text" name="event_location" id="pac-input" placeholder="Search location here">
                                 <div id="location-map"></div>
                                 <span id="event_locationError"><strong style="color: red"></strong></span>
                              </div>
                           </div>
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
               <div class="field-group row activities-example">
                  <div class="col-sm-3 col-md-3 col-lg-3">
                     <div class="field-input">
                        <label for="activity">Activity<span class="required"> (Example)</span></label>
                        <input type="text" placeholder="Enter activity" value="Opening Session" disabled>
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-3 col-lg-3">
                     <div class="field-input">
                        <label for="action">Description<span class="required"> (Example)</span></label>
                        <input type="text" placeholder="Enter action" value="Guests Arrive" disabled>
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-3 col-lg-3">
                     <div class="field-input">
                        <label for="start time">Start Time<span class="required"> (Example)</span></label>
                        <input type="text" value="10:00 AM" disabled>
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-3 col-lg-2">
                     <div class="field-input">
                        <label for="activity_end_time">End Time<span class="required"> (Example)</span></label>
                        <input type="text" value="10:30 AM" disabled>
                     </div>
                  </div>
               </div>
               <div id="activities">
                  <div class="field-group row">
                     <div class="col-sm-3 col-md-3 col-lg-3">
                        <div class="field-input">
                           <label for="activity">Activity<span class="required">*</span></label>
                           <input type="text" placeholder="Enter activity name" name="activity[0]" id="activity" value="{{ old('activity[0]') }}" onchange="clearError(this)">
                           <span id="activity-0-Error"><strong style="color: red"></strong></span>
                        </div>
                     </div>
                     <div class="col-sm-12 col-md-3 col-lg-3">
                        <div class="field-input">
                           <label for="action">Description<span class="required">*</span></label>
                           <input type="text" placeholder="Enter activity description" name="action[0]" id="action" value="{{ old('action[0]') }}" onchange="clearError(this)">
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
                           <input type="text" placeholder="Enter Phone Number" name="program_contact_phone[0]" id="program_contact_phone" onchange="clearError(this)">
                           <span id="program_contact_phone-0-Error"><strong style="color: red"></strong></span>
                        </div>
                     </div>
                     <div class="col-sm-12 col-md-1 program-contact-delete" style="margin-top: 20px; float: right;">
                        <i class="fas fa-trash"></i>
                     </div>
                  </div>
               </div>
               <div class="field-submit">
                  <a href="{{ route('home') }}" class="btn" style="background-color: red">Cancel</a>
                  <input type="submit" value="Submit" id="create-program-submit" class="btn">
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
   let j = 0;
   let k = 2;
   var mapInstance;
   var marker;

   let lat = document.getElementById('lat').innerHTML ? document.getElementById('lat').innerHTML : '-1.270104'
   let long = document.getElementById('long').innerHTML ? document.getElementById('long').innerHTML : '36.80814'

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

   function initMap() {
      var latLng = new google.maps.LatLng(lat, long);
      var map = new google.maps.Map(document.getElementById('location-map'), {
         center: {lat: parseFloat(lat), lng: parseFloat(long)},
         zoom: 17
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

      geocoder = new google.maps.Geocoder;

      geocoder.geocode({
         'location': latLng
      }, function(results, status) {
         console.log(status)
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
         add_activity_html += '<label for="activity">Activity<span class="required">*</span></label>'
         add_activity_html += '<input type="text" placeholder="Enter activity name" name="activity['+i+']" id="activity" required onchange="clearError(this)">'
         add_activity_html += '<span id="activity-'+i+'-Error"><strong style="color: red"></strong></span>'
         add_activity_html += '</div>'
         add_activity_html += '</div>'
         add_activity_html += '<div class="col-sm-12 col-md-3 col-lg-3">'
         add_activity_html += '<div class="field-input">'
         add_activity_html += '<label for="action">Decription<span class="required">*</span></label>'
         add_activity_html += '<input type="text" placeholder="Enter activity description" name="action['+i+']" id="action" required onchange="clearError(this)">'
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
      j++
      e.preventDefault()
      let add_contact_html = '<div class="field-group row">'
         add_contact_html += '<div class="col-sm-12 col-md-6">'
         add_contact_html += '<div class="field-input">'
         add_contact_html += '<label for="program_contact_name">Name<span class="required">*</span></label>'
         add_contact_html += '<input type="text" placeholder="Enter Contact\'s Name" name="program_contact_name['+j+']" id="program_contact_name" required onchange="clearError(this)">'
         add_contact_html += '<span id="program_contact_name-'+j+'-Error"><strong style="color: red"></strong></span>'
         add_contact_html += '</div>'
         add_contact_html += '</div>'
         add_contact_html += '<div class="col-sm-12 col-md-5">'
         add_contact_html += '<div class="field-input">'
         add_contact_html += '<label for="program_contact_phone">Phone Number<span class="required">*</span></label>'
         add_contact_html += '<input type="number" placeholder="Enter Phone Number" name="program_contact_phone['+j+']" id="program_contact_phone" required onchange="clearError(this)">'
         add_contact_html += '<span id="program_contact_phone-'+j+'-Error"><strong style="color: red"></strong></span>'
         add_contact_html += '</div>'
         add_contact_html += '</div>'
         add_contact_html += '<div class="col-sm-12 col-md-1 program-contact-delete" style="margin-top: 20px; float: right;">'
         add_contact_html += '<i class="fas fa-trash"></i>'
         add_contact_html += '</div>'
         add_contact_html += '</div>'

      $(add_contact_html).appendTo(contacts)
   })

   $(document.body).on("click", ".program-activity-delete", function(event){
      let btn = $(this);
      let item = btn.parent();
      if (activities.children().length > 1) item.remove();
   });

   $(document.body).on("click", ".program-contact-delete", function(event){
      let $btn = $(this);
      let $item = $btn.parent();
      if (contacts.children().length > 1) $item.remove();
   });

   $('#event_type').on('change', function() {
      if ($(this).val() == 'Other') {
         $('#custom-event-input').removeAttr('hidden')
      } else {
         $('#custom-event-input').attr('hidden', 'hidden')
      }
   })

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
      $(element).parent().children('span').text('')
   }

   $("#create-program-submit").on("click", function(e) {
      // Prevent normal submit action
      e.preventDefault();

      // Collect current latlng of marker and put in hidden form field
      if (marker) {
         $("#event_location").val(marker.getPosition());
      } else {
         alert('Please select the location')
         return
      }

      $("#create-program-submit").val('Please Wait...')
      $("#create-program-submit").attr('disabled', 'disabled')

      let formData = $('#add_program').serializeArray()

      let newKey = ''

      $.ajax({
         method: "POST",
         dataType: "json",
         headers: {
            Accept: "application/json"
         },
         url: "{{ route('client.program.store') }}",
         data: formData,
         success: ({ redirectPath }) => window.location.assign(redirectPath),
         error: (response) => {
            $("#create-program-submit").val('Submit')
            $("#create-program-submit").removeAttr('disabled')
            if(response.status === 422) {
               let errors = response.responseJSON;
               console.log(errors)
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

