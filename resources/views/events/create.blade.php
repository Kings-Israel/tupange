@extends('layouts.master')
@section('title', 'Create Event')
@section('css')
<style>
   .link-to-dashboard {
      float: right;
   }
   #add-event {
      float: right;
   }
   @media only screen and (max-width: 768px) {
      .container {
         margin-top: 20px;
      }
   }
   @media only screen and (max-width: 575px) {
      .container {
         margin-top: 2px;
      }
      .link-to-dashboard {
         float: left;
         margin-left: -10px;
      }
      .cancel-btn {
         width: 100%;
         margin-top: 70px;
      }
      #add-event {
         width: 100%;
         margin-top: -135px;
      }
      #add-event-and-search {
         width: 100%;
         margin-top: -160px;
      }
   }
</style>
@endsection
@section('content')
<div class="container">
   <br>
    <div class="row">
    <div class="col-lg-2"></div>
    <div class="col-lg-8">
       <div class="billing-form">
           <div class="row">
              <div class="col-lg-4 col-sm-12 col-md-6">
                 <h1>Add Event Details</h1>
              </div>
              <div class="col-lg-8 col-sm-12 col-md-6">
                  @auth
                     <a href="{{ route('events.index') }}" class="btn link-to-dashboard">
                        Go to My Events Dashboard
                     </a>
                  @else
                     <span class="link-to-dashboard" style="color: red">*Log In is required</span>
                  @endauth
              </div>
           </div>
            <form class="form" action="{{ route('events.store') }}" method="POST" id="event_add" enctype="multipart/form-data">
                @csrf
                <div class="field-group">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-input">
                                <label for="event_name">Event Name<span class="required">*</span></label>
                                <input type="text" placeholder="Enter your event name" name="event_name" id="event_name" value="{{ old('event_name') }}" required>
                                <span id="event_nameError">
                                    <strong class="error-message"></strong>
                                 </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-group field-input">
                                <label for="company_name">Event Type<span class="required">*</span></label>
                                @if ($event_type != "")
                                 <select name="event_type" id="event_type" class="form-control">
                                    <option value="" disabled>Select Event Type</option>
                                    @foreach ($eventTypes as $event)
                                       <option value="{{ $event }}" {{ $event_type == $event ? 'selected' : '' }}>{{ $event }}</option>
                                    @endforeach
                                 </select>
                                 @else
                                 <select name="event_type" id="event_type" class="form-control">
                                    <option value="" disabled>Select Event Type</option>
                                    @foreach ($eventTypes as $event)
                                     <option value="{{ $event->name }}" {{ old('event_type') == $event->name ? 'selected' : '' }}>{{ $event->name }}</option>
                                    @endforeach
                                    <option value="Other" {{ old('event_type') === 'Other' ? 'selected' : '' }}>Other</option>
                                 </select>
                                 @endif
                                 <span id="event_typeError">
                                    <strong class="error-message"></strong>
                                 </span>
                            </div>
                           @if ($event_type != '' && $event_type == 'Other')
                              <div class="field-input" id="custom-event-input">
                                 <label for="event_type">Custom Event Type</label>
                                 <input type="text" placeholder="Enter event type" name="event_type_custom" id="event_type_custom" value="{{ $event_type_custom }}">
                                 <span id="event_type_customError">
                                    <strong class="error-message"></strong>
                                 </span>
                              </div>
                           @else
                              <div class="field-input" id="custom-event-input" hidden>
                                 <label for="event_type">Custom Event Type</label>
                                 <input type="text" placeholder="Enter event type" name="event_type_custom" id="event_type_custom" value="{{ old('event_type_custom') }}">
                                 <span id="event_type_customError">
                                    <strong class="error-message"></strong>
                                 </span>
                              </div>
                           @endif
                        </div>
                        <div class="col-md-6">
                            <div class="field-input">
                                <label for="email">Event Starts <span class="required">*</span></label>
                                @if ($event_start_date != "")
                                 <input type="date" placeholder="DD/MM/YYYY"  name="event_start_date" id="event_start_date" value="{{ $event_start_date }}" required>
                                @else
                                 <input type="date" placeholder="DD/MM/YYYY"  name="event_start_date" id="event_start_date" value="{{ old('event_start_date') }}" required>
                                @endif
                                <x-input-error for="event_start_date"></x-input-error>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-input">
                                <label for="email">Time<span class="required">*</span></label>
                                <input type="time" name="event_start_time" id="event_start_time" value="{{ old('event_start_time') }}" required>
                                <span id="event_start_timeError">
                                    <strong class="error-message"></strong>
                                 </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-input">
                                <label for="email">Event Ends<span class="required">*</span></label>
                                <input type="date" placeholder="DD/MM/YYYY" name="event_end_date" id="event_end_date" value="{{ old('event_end_date') }}" required>
                                <span id="event_end_dateError">
                                    <strong class="error-message"></strong>
                                 </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-input">
                                <label for="start time">Time<span class="required">*</span></label>
                                <input type="time" name="event_end_time" id="event_end_time" value="{{ old('event_end_time') }}" required>
                                <span id="event_end_timeError">
                                    <strong class="error-message"></strong>
                                 </span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="field-input">
                                <label for="email">Event Description</label>
                                <input type="text"  placeholder="Describe your event. Guests will be able to see this" name="event_description" id="event_description" value="{{ old('event_description') }}" required>
                                <span id="event_descriptionError">
                                    <strong class="error-message"></strong>
                                 </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-input">
                                <label for="event_name">Expected Guests</label>
                                <input type="number" placeholder="Number Expected Guests" name="event_guests_expected" id="event_guests_expected" min="1" value="{{ old('event_guests_expected') }}" required>
                                <span id="event_guests_expectedError">
                                    <strong class="error-message"></strong>
                                 </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-input">
                                <label for="event_type">Maximum Guests</label>
                                <input type="number" placeholder="Limit Number Of Guests" name="event_guests_max" id="event_guests_max" min="1" value="{{ old('event_guests_max') }}">
                                <span id="event_guests_maxError">
                                    <strong class="error-message"></strong>
                                 </span>
                            </div>
                        </div>
                        <div class="listing-box" id="media">
                           <div class="row">
                              <div class="col-md-6 col-sm-12">
                                 <div class="field-group field-file">
                                     <label for="event_poster">Event Poster</label>
                                     <label for="event_poster" class="preview">
                                         <input type="file" id="event_poster" name="event_poster" class="upload-file" data-max-size="50000" accept=".jpg, .jpeg,.png" value="{{ old('event_poster') }}">
                                         <img class="img_preview" src="{{ asset('assets/images/no-image.png') }}" alt="" />
                                         <i class="la la-cloud-upload-alt"></i>
                                     </label>
                                     <div class="field-note">Maximum file size: 5 MB.</div>
                                       <span id="event_posterError">
                                          <strong class="error-message"></strong>
                                       </span>
                                 </div>
                              </div>
                              <div class="col-md-6 col-sm-12">
                                 <div class="field-group field-check">
                                    <label for="corporate_event">
                                        <input type="checkbox" name="corporate_event" id="corporate_event" value="corporate_event" onchange="addCorporateInfo(this)">Corporate Event
                                        <span class="checkmark">
                                          <i class="la la-check"></i>
                                        </span>
                                        <div class="field-note">Is this a corporate event?</div>
                                    </label>
                                </div>
                                <div class="comapny_information" hidden>
                                   <div class="field-input">
                                      <label for="company_name">Company Name<span class="required">*</span></label>
                                      <input type="text" placeholder="Enter the company name" name="corporate_company_name" id="company_name" value="{{ old('corporate_company_name') }}">
                                       <span id="corporate_company_nameError">
                                          <strong class="error-message"></strong>
                                       </span>
                                   </div>
                                   <div class="field-input">
                                      <label for="company_address">Company Address<span class="required">*</span></label>
                                      <input type="text" placeholder="Enter the company's address" name="corporate_company_address" id="company_address" value="{{ old('corporate_company_address') }}">
                                       <span id="corporate_company_addressError">
                                          <strong class="error-message"></strong>
                                       </span>
                                  </div>
                                </div>
                              </div>
                           </div>
                       </div><!-- .listing-box -->
                        <div class="listing-box" id="location">
                            <h3>Location</h3>
                            <div class="field-group field-maps">
                                <div class="field-inline">
                                    <label for="pac-input">Enter Event Location*</label>
                                </div>
                                <div class="field-map">
                                    <input id="place_id" type="hidden" name="place_id">
                                    <input type="text" name="event_location" id="pac-input" placeholder="Search location here">
                                    <div id="location-map"></div>
                                    <span id="event_locationError">
                                       <strong class="error-message"></strong>
                                    </span>
                                </div>
                            </div>
                        </div><!-- .listing-box -->

                        <div class="field-group field-input">
                           <label>Invitation custom message</label>
                           <textarea name="event_custom_message" id="" class="form-control" placeholder="This message will be sent to all invited guests...">{{ old('event_custom_message') }}</textarea>
                           <span id="event_custom_messageError">
                              <strong class="error-message"></strong>
                           </span>
                       </div>
                    </div>
                </div>
                <div class="field-submit">
                  <input type="hidden" name="action" id="action">
                   <div class="row">
                      <div class="col-lg-6 col-md-6 col-sm-12">
                         <a href="{{ route('events.index') }}" class="btn cancel-btn" style="background-color: red; color:white">Cancel</a>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-12">
                        <input type="submit" name="save" value="Save" id="add-event" class="btn">
                        <input type="submit" name="save" value="Save and Search Services" id="add-event-and-search" class="btn" style="margin-right: 5px; background-color: #F58C1C">
                      </div>
                   </div>
                </div>
            </form><!-- .billingForm -->
            <span class="open-login" hidden href="{{ route('login') }}">Login</span>
            <br>
        </div><!-- .checkout-form -->
    </div>
    <div class="col-lg-2"></div>
</div>
@push('scripts')
<script>
   var mapInstance;
   var marker;

   function addCorporateInfo(value) {
      if (value.checked) {
         $('.comapny_information').removeAttr('hidden')
      } else {
         $('.comapny_information').attr('hidden', 'hidden')
      }
   }

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

   $('#event_type').on('change', function() {
      if ($(this).val() == 'Other') {
         $('#custom-event-input').removeAttr('hidden')
      } else {
         $('#custom-event-input').attr('hidden', 'hidden')
      }
   })

   // function initMap() {
   //    var latlng = new google.maps.LatLng(-1.270104, 36.80814);
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

   //       markers = [];

   //       // Clear out the old markers.
   //       markers.forEach(function(marker) {
   //             marker.setMap(null);
   //       });

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
   //                map: mapInstance,
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
   //       mapInstance.fitBounds(bounds);
   //    });
   //    mapInstance = new google.maps.Map(document.getElementById("location-map"), mapOptions);

   //    google.maps.event.addListener(mapInstance, 'click', function(event) {
   //       placeMarker(event.latLng);
   //    });
   // }

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
   var mm = today.getMonth() + 1; //January is 0 so need to add 1 to make it 1!
   var yyyy = today.getFullYear();
   if(dd<10){
   dd='0'+dd
   }
   if(mm<10){
   mm='0'+mm
   }

   today = yyyy+'-'+mm+'-'+dd;

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

   var dateClass='#event_start_date';

   if (document.querySelector(dateClass).type == 'date')
   {
      var oCSS = document.createElement('link');
      oCSS.type='text/css'; oCSS.rel='stylesheet';
      oCSS.href='//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.css';
      oCSS.onload=function()
      {
         var oJS = document.createElement('script');
         oJS.type='text/javascript';
         oJS.src='//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js';
         oJS.onload=function()
         {
         $(dateClass).datepicker();
         }
         document.body.appendChild(oJS);
      }
   }

   $('#add-event-and-search').on('click', function() {
      $('#action').val('search-services')
   })

   $('#add-event').on('click', function() {
      $('#action').val('')
   })

   $("#event_add").on("submit", function(e) {
      // Prevent normal submit action
      e.preventDefault();
      // Collect current latlng of marker and put in hidden form field
      if (marker) {
         $("#event_location").val(marker.getPosition());
      } else {
         alert('Please select the location')
         return
      }

      $('#add-event').val('Please Wait...')
      $('#add-event').attr('disabled', 'disabled')

      $('#add-event-and-search').val('Please Wait...')
      $('#add-event-and-search').attr('disabled', 'disabled')

      $.ajax({
         method: "POST",
         dataType: "json",
         headers: {
            Accept: "application/json"
         },
         url: "{{ route('events.store') }}",
         data: new FormData(this),
         processData: false,
         cache: false,
         contentType: false,
         success: (response) => {
            if (response.redirectPath === '') {
               $('.open-login').click()
               toastr.options =
               {
                  "closeButton" : true,
                  "progressBar" : true,
                  "positionClass" : "toast-bottom-right"
               }
               toastr.success(response.message);
            } else {
               window.location.assign(response.redirectPath)
            }
            $('#add-event').val('Save')
            $('#add-event').removeAttr('disabled')
            $('#add-event-and-search').val('Save and Search Services')
            $('#add-event-and-search').removeAttr('disabled')
         },
         error: (response) => {
            $('#add-event').val('Save')
            $('#add-event').removeAttr('disabled')
            $('#add-event-and-search').val('Save and Search Services')
            $('#add-event-and-search').removeAttr('disabled')
            if(response.status === 422) {
               let errors = response.responseJSON.errors;
               Object.keys(errors).forEach(function (key) {
                  $("#" + key + "Error").children("strong").text(errors[key][0]);
               });

               toastr.options =
                     {
                        "closeButton" : true,
                        "progressBar" : true,
                        "positionClass" : "toast-bottom-right"
                     }
               toastr.error("Please check the data and try again");
            } else if (response.status === 400) {
               toastr.options =
                     {
                        "closeButton" : true,
                        "progressBar" : true,
                        "positionClass" : "toast-bottom-right"
                     }
               toastr.error("Please check the data and try again");
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

