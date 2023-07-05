
@extends('layouts.master')
@section('title', 'Create Program')
@section('css')
   <style>
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
         <div class="row">
            <div class="col-6">
               <h1>Program Details</h1>
            </div>
         </div>
         <form class="form" action="{{ route('client.submit.program') }}" method="POST" id="create_program" enctype="multipart/form-data">
            @csrf
            <div class="field-group">
               <div class="row">
                  <div class="col-md-6">
                     <div class="field-input">
                        <label for="event_name">Event Name<span class="required">*</span></label>
                        <input type="text" placeholder="Enter your event name" name="event_name" id="event_name" value="{{ old('event_name') }}" onchange="clearError(this)">
                        <span id="event_name-Error"><strong style="color: red"></strong></span>
                     </div>
                  </div>
                  <div class="col-md-6">
                        <div class="field-group field-input">
                           <label for="company_name">Event Type <span class="required">*</span></label>
                           <select name="event_type" id="event_type" class="form-control">
                              <option value="">Select Event Type</option>
                              @foreach ($eventTypes as $eventType)
                                 <option value="{{ $eventType }}">{{ $eventType }}</option>
                              @endforeach
                           </select>
                           <span id="event_type-Error"><strong style="color: red"></strong></span>
                           <div class="field-input mt-1" id="custom-event-input" hidden>
                              <label for="event_type">Custom Event Type</label>
                              <input type="text" placeholder="Enter event type" name="event_type_custom" id="event_type_custom" value="{{ old('event_type_custom') }}">
                           </div>
                           <span id="event_type_custom-Error"><strong style="color: red"></strong></span>
                        </div>
                  </div>
                  <div class="col-md-6">
                        <div class="field-input">
                           <label for="email">Event Starts <span class="required">*</span></label>
                           <input type="date" placeholder="DD/MM/YYYY"  name="event_start_date" id="event_start_date" value="{{ old('event_start_date') ? old('event_start_date')->format('Y-m-d') : '' }}" onchange="clearError(this)">
                           <span id="event_start_date-Error"><strong style="color: red"></strong></span>
                        </div>
                  </div>
                  <div class="col-md-6">
                        <div class="field-input">
                           <label for="email">Time<span class="required">*</span></label>
                           <input type="time" name="event_start_time" id="event_start_time" value="{{ old('event_start_date') ? old('event_start_date')->format('H:i') : '' }}" onchange="clearError(this)">
                           <span id="event_start_time-Error"><strong style="color: red"></strong></span>
                        </div>
                  </div>
                  <div class="col-md-6">
                        <div class="field-input">
                           <label for="email">Event Ends <span class="required">*</span></label>
                           <input type="date" placeholder="DD/MM/YYYY" name="event_end_date" id="event_end_date" value="{{ old('event_end_date') ? old('event_end_date')->format('Y-m-d') : '' }}" onchange="clearError(this)">
                           <span id="event_end_date-Error"><strong style="color: red"></strong></span>
                        </div>
                  </div>
                  <div class="col-md-6">
                        <div class="field-input">
                           <label for="start time">Time<span class="required">*</span></label>
                           <input type="time" name="event_end_time" id="event_end_time" value="{{ old('event_end_date') ? old('event_end_date')->format('H:i') : '' }}" onchange="clearError(this)">
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
                     <div class="field-inline">
                        <label for="pac-input">Please enter the event location/address*</label>
                     </div>
                     <div class="field-map">
                        <input name="place_id" id="place_id" type="hidden">
                        <input type="text" name="event_location" id="pac-input" id="event_location" placeholder="Search place to find on map">
                        <div id="location-map"></div>
                     </div>
                     <span id="event_location-Error"><strong style="color: red"></strong></span>
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
               <div class="field-group row">
                  <div class="col-sm-3 col-md-3 col-lg-3">
                     <div class="field-input">
                        <label for="activity">Activity<span class="required">*</span></label>
                        <input type="text" placeholder="Enter activity name" name="activity[0]" id="activity" value="{{ old('activity[0]') }}" onchange="clearError(this)">
                        <span id="activity[0]-Error"><strong style="color: red"></strong></span>
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-3 col-lg-3">
                     <div class="field-input">
                        <label for="action">Action<span class="required">*</span></label>
                        <input type="text" placeholder="Enter action" name="action[0]" id="action" value="{{ old('action[0]') }}" onchange="clearError(this)">
                        <span id="action[0]-Error"><strong style="color: red"></strong></span>
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-3 col-lg-3">
                     <div class="field-input">
                        <label for="start time">Start Time<span class="required">*</span></label>
                        <input type="time" name="activity_start_time[0]" id="activity_start_time" value="{{ old('activity_start_time[0]') }}" onchange="clearError(this)">
                        <span id="activity_start_time[0]-Error"><strong style="color: red"></strong></span>
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-3 col-lg-2">
                     <div class="field-input">
                        <label for="activity_end_time">End Time<span class="required">*</span></label>
                        <input type="time" name="activity_end_time[0]" id="activity_end_time" value="{{ old('activity_end_time[0]') }}" onchange="clearError(this)">
                        <span id="activity_end_time[0]-Error"><strong style="color: red"></strong></span>
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
                        <span id="program_contact_name[0]-Error"><strong style="color: red"></strong></span>
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-5">
                     <div class="field-input">
                        <label for="program_contact_phone">Phone Number<span class="required">*</span></label>
                        <input type="number" placeholder="Enter Phone Number" name="program_contact_phone[0]" id="program_contact_phone" onchange="clearError(this)">
                        <span id="program_contact_phone[0]-Error"><strong style="color: red"></strong></span>
                     </div>
                  </div>
                  <div class="col-sm-12 col-md-1 program-contact-delete" style="margin-top: 20px; float: right;">
                     <i class="fas fa-trash"></i>
                  </div>
               </div>
            </div>
            <span class="error-message">
               *Please note that you will be charged Ksh.100 to download this event program.
            </span>
            <div class="row">
               <div class="col-lg-3 col-md-6 col-sm-12">
                  <div class="field-input">
                     <label for="Name">Enter Your First Name</label>
                     <input type="text" name="first_name" id="first_name" class="form-control w-100">
                     <span id="first_name-Error"><strong style="color: red"></strong></span>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6 col-sm-12">
                  <div class="field-input">
                     <label for="Name">Enter Your Last Name</label>
                     <input type="text" name="last_name" id="last_name" class="form-control w-100">
                     <span id="last_name-Error"><strong style="color: red"></strong></span>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6 col-sm-12">
                  <div class="field-input">
                     <label for="Name">Enter Your Email Address</label>
                     <input type="email" name="email" id="email" class="form-control w-100">
                     <span id="email-Error"><strong style="color: red"></strong></span>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6 col-sm-12">
                  <div class="field-input">
                     <label for="">Enter Your phone number</label>
                     <input type="text" name="phone_number" class="form-control w-100" placeholder="e.g 254700454000">
                     <span id="phone_number-Error"><strong style="color: red"></strong></span>
                  </div>
               </div>
            </div>
            <div class="field-submit">
               <a href="{{ route('home') }}" class="btn" style="background-color: red">Cancel</a>
               <input type="submit" value="Proceed" id="create-program-submit" class="btn">
            </div>
         </form><!-- .billingForm -->
      </div><!-- .checkout-form -->
   </div>
   <div class="col-lg-1"></div>
</div>
@push('scripts')
<script>
   let i = 0;
   let j = 0;
   var mapInstance;
   var marker;

   let lat = '-1.270104'
   let long = '36.80814'

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
      var map = new google.maps.Map(document.getElementById('location-map'), {
         center: {lat: -1.270104, lng: 36.80814},
         zoom: 13
      });
      var input = document.getElementById('pac-input');

      var autocomplete = new google.maps.places.Autocomplete(input);
      autocomplete.bindTo('bounds', map);

      var infowindow = new google.maps.InfoWindow();
      marker = new google.maps.Marker({
         map: map,
         anchorPoint: new google.maps.Point(0, -29)
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

   $('#event_type').on('change', function() {
      if ($(this).val() == 'Other') {
         $('#custom-event-input').removeAttr('hidden')
      } else {
         $('#custom-event-input').attr('hidden', 'hidden')
      }
   })

   let activities = $('#activities')

   $(document.body).on('click', '#program-add-activity', function(e) {
      i++
      e.preventDefault()
      let add_activity_html = '<div class="field-group row" id="activities">'
         add_activity_html += '<div class="col-sm-3 col-md-3 col-lg-3">'
         add_activity_html += '<div class="field-input">'
         add_activity_html += '<label for="activity">Activity<span class="required">*</span></label>'
         add_activity_html += '<input type="text" placeholder="Enter activity name" name="activity['+i+']" id="activity" required onchange="clearError(this)">'
         add_activity_html += '<span id="program_contact_name['+i+']-Error"><strong style="color: red"></strong></span>'
         add_activity_html += '</div>'
         add_activity_html += '</div>'
         add_activity_html += '<div class="col-sm-12 col-md-3 col-lg-3">'
         add_activity_html += '<div class="field-input">'
         add_activity_html += '<label for="action">Action<span class="required">*</span></label>'
         add_activity_html += '<input type="text" placeholder="Enter action" name="action['+i+']" id="action" required onchange="clearError(this)">'
         add_activity_html += '<span id="action['+i+']-Error"><strong style="color: red"></strong></span>'
         add_activity_html += '</div>'
         add_activity_html += '</div>'
         add_activity_html += '<div class="col-sm-12 col-md-3 col-lg-3">'
         add_activity_html += '<div class="field-input">'
         add_activity_html += '<label for="activity_start_time">Start Time<span class="required">*</span></label>'
         add_activity_html += '<input type="time" name="activity_start_time['+i+']" id="activity_start_time" required onchange="clearError(this)">'
         add_activity_html += '<span id="activity_start_time['+i+']-Error"><strong style="color: red"></strong></span>'
         add_activity_html += '</div>'
         add_activity_html += '</div>'
         add_activity_html += '<div class="col-sm-12 col-md-3 col-lg-2">'
         add_activity_html += '<div class="field-input">'
         add_activity_html += '<label for="activity_end_time">End Time<span class="required">*</span></label>'
         add_activity_html += '<input type="time" name="activity_end_time['+i+']" id="activity_end_time" required onchange="clearError(this)">'
         add_activity_html += '<span id="activity_end_time['+i+']-Error"><strong style="color: red"></strong></span>'
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
         add_contact_html += '<span id="program_contact_name['+i+']-Error"><strong style="color: red"></strong></span>'
         add_contact_html += '</div>'
         add_contact_html += '</div>'
         add_contact_html += '<div class="col-sm-12 col-md-5">'
         add_contact_html += '<div class="field-input">'
         add_contact_html += '<label for="program_contact_phone">Phone Number<span class="required">*</span></label>'
         add_contact_html += '<input type="number" placeholder="Enter Phone Number" name="program_contact_phone['+j+']" id="program_contact_phone" required onchange="clearError(this)">'
         add_contact_html += '<span id="program_contact_phone['+i+']-Error"><strong style="color: red"></strong></span>'
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

      $('#create-program-submit').val('Please wait...')
      $('#create-program-submit').attr('disabled', 'disabled')

      // $("#add_program").submit();

      let formData = $('#create_program').serializeArray()

      let newKey = ''

      $.ajax({
         method: "POST",
         dataType: "json",
         headers: {
            Accept: "application/json"
         },
         url: "{{ route('client.submit.program') }}",
         data: formData,
         success: ({ redirectPath }) => window.location.assign(redirectPath),
         error: (response) => {
            if(response.status === 422) {
               $('#create-program-submit').val('Proceed')
               $('#create-program-submit').removeAttr('disabled')
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
               $('#create-program-submit').val('Proceed')
               $('#create-program-submit').removeAttr('disabled')
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
