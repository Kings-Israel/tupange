@extends('layouts.master')
@section('title', 'Edit Event')
@section('content')
<div class="container">
    <div class="row">
    <div class="col-lg-2"></div>
    <div class="col-lg-8">
        <div class="billing-form">
            <div class="row mb-3">
                <div class="col-lg-6">
                    <h2>Event Details</h2>
                </div>
                <div class="col-lg-6">
                    <a href="{{ route('events.show', $event) }}" style="float: right">
                        <button class="btn">Back to My Event</button>
                    </a>
                </div>
            </div>
            <form class="form" action="{{ route('events.update', $event->id) }}" method="POST" id="event_add" enctype="multipart/form-data">
                @csrf
                <p id="event_lat" hidden>{{ $event->event_location_lat }}</p>
                <p id="event_long" hidden>{{ $event->event_location_long }}</p>
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <div class="field-group">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-input">
                                <label for="event_name">Event Name<span class="required">*</span></label>
                                <input type="text" placeholder="Enter your event name" name="event_name" id="event_name" value="{{ $event->event_name }}" required>
                                <span id="event_nameError">
                                    <strong class="error-message"></strong>
                                 </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-group field-input">
                                 <label for="company_name">Event Type<span class="required">*</span>@if (!collect($eventTypes)->contains($event->event_type))(Custom)@endif</label>
                                 @if (collect($eventTypes)->contains($event->event_type))
                                    <select name="event_type" id="event_type" class="form-control">
                                       @foreach ($eventTypes as $type)
                                          <option value="{{ $type }}" {{ $event->event_type == $type ? 'selected' : '' }}>{{ $type }}</option>
                                       @endforeach
                                    </select>
                                    <span id="event_typeError">
                                       <strong class="error-message"></strong>
                                    </span>
                                 @else
                                    <div class=" field-group field-input" id="custom-event-input">
                                       <input type="text" placeholder="Enter event type" name="event_type" id="event_type" value="{{ $event->event_type }}">
                                    </div>
                                    <span id="event_typeError">
                                       <strong class="error-message"></strong>
                                    </span>
                                 @endif
                                 <div class=" field-group field-input mt-1" id="custom-event-type-input" hidden>
                                    <label for="event_type">Custom Event Type</label>
                                    <input type="text" placeholder="Enter event type" name="custom_event_type" value="">
                                 </div>
                                 <span id="custom_event_typeError">
                                    <strong class="error-message"></strong>
                                 </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-input">
                                <label>Event Starts <span class="required">*</span></label>
                                <input type="date" placeholder="DD/MM/YYYY"  name="event_start_date" id="event_start_date" value="{{ $event->start_date }}" required>
                                <span id="event_start_dateError">
                                    <strong class="error-message"></strong>
                                 </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-input">
                                <label>Start Time<span class="required">*</span></label>
                                <input type="time" name="event_start_time" id="event_start_time" value="{{ $event->start_time }}" required>
                                <span id="event_start_timeError">
                                    <strong class="error-message"></strong>
                                 </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-input">
                                <label>Event Ends <span class="required">*</span></label>
                                <input type="date" placeholder="DD/MM/YYYY" name="event_end_date" id="event_end_date" value="{{ $event->end_date }}" required>
                                <x-input-error for="event_event_end_date"></x-input-error>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-input">
                                <label>End Time<span class="required">*</span></label>
                                <input type="time" name="event_end_time" id="event_end_time" value="{{ $event->end_time }}" required>
                                <span id="event_end_dateError">
                                    <strong class="error-message"></strong>
                                 </span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="field-input">
                                <label>Event Description</label>
                                <input type="text"  placeholder="Describe your event. Guests will be able to see this" autocomplete="off" name="event_description" id="event_description" value="{{ $event->event_description }}" required>
                                 <span id="event_descriptionError">
                                    <strong class="error-message"></strong>
                                 </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-input">
                                <label for="event_name">Expected Guests</label>
                                <input type="number" placeholder="Number Expected Guests" name="event_guests_expected" id="event_guests_expected" min="1" value="{{ $event->settings->events_guests_expected }}" required>
                                <span id="event_guests_expectedError">
                                 <strong class="error-message"></strong>
                              </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="field-input">
                                <label for="event_type">Maximum Guests</label>
                                <input type="number" placeholder="Limit Number Of Guests" name="event_guests_max" id="event_guests_max" min="1" value="{{ $event->settings->events_guests_max }}">
                                <span id="event_guests_maxError">
                                    <strong class="error-message"></strong>
                                 </span>
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
                                 <input type="text" name="event_location" id="pac-input" value="{{ $event->event_location }}">
                                 <div id="location-map"></div>
                                 <span id="event_locationError">
                                    <strong class="error-message"></strong>
                                 </span>
                              </div>
                           </div>
                        </div><!-- .listing-box -->
                        <div class="listing-box">
                           <div class="row">
                              <div class="col-lg-4 col-md-4 col-sm-12">
                                 <div class="field-group field-file">
                                     <label for="event_poster">Event Poster</label>
                                     <label for="event_poster" class="preview">
                                         <input type="file" id="event_poster" name="event_poster" class="upload-file" accept=".jpg,.jpeg,.png" data-max-size="50000" value="{{ $event->getEventCoverImage($event->event_poster) }}">
                                         <img class="img_preview" src="{{ $event->getEventCoverImage($event->event_poster) }}" alt="" />
                                         <i class="la la-cloud-upload-alt"></i>
                                     </label>
                                     <div class="field-note">Maximum file size: 5 MB.</div>
                                     <span id="event_posterError">
                                       <strong class="error-message"></strong>
                                    </span>
                                 </div>
                              </div>
                              <div class="col-lg-8 col-md-8 col-sm-12">
                                 <div class="field-group field-input">
                                    <label for="company_logo">Invitation custom message</label>
                                    <textarea name="event_custom_message" id="" class="form-control" placeholder="This message will be sent to all invited guests...">{{ $event->custom_message }}</textarea>
                                    <x-input-error for="event_custom_message"></x-input-error>
                                </div>
                              </div>
                           </div>
                        </div><!-- .listing-box -->
                        <div class="listing-box">
                           <div class="row">
                              <div class="col-lg-4 col-md-4 col-sm-12">
                                 <div class="field-group field-check">
                                    <label for="corporate_event">
                                       <input type="checkbox" name="corporate_event" id="corporate_event" value="corporate_event" {{ $event->isCorporate == true ? 'checked' : '' }} onchange="addCorporateInfo(this)">Corporate Event
                                       <span class="checkmark">
                                       <i class="la la-check"></i>
                                       </span>
                                       <div class="field-note">Is this a corporate event?</div>
                                    </label>
                                </div>
                              </div>
                              <div class="col-lg-8 col-md-8 col-sm-12">
                                 <div class="comapny_information" @if($event->isCorporate == false ) hidden @endif>
                                    <div class="field-input">
                                       <label for="company_name">Company Name<span class="required">*</span></label>
                                       <input type="text" placeholder="Enter the company name" name="corporate_company_name" id="corporate_company_name" value="{{ $event->corporate_company_name }}">
                                       <span id="corporate_company_nameError">
                                          <strong class="error-message"></strong>
                                       </span>
                                    </div>
                                    <div class="field-input">
                                       <label for="company_address">Comapny Address<span class="required">*</span></label>
                                       <input type="text" placeholder="Enter the company's address" name="corporate_company_address" id="corporate_company_address" value="{{ $event->corporate_company_address }}">
                                       <span id="corporate_company_addressError">
                                          <strong class="error-message"></strong>
                                       </span>
                                   </div>
                                 </div>
                              </div>
                           </div>
                        </div><!-- .listing-box -->
                    </div>
                </div>
                <div class="field-submit d-flex justify-content-between">
                  <a href="{{ route('events.show', $event) }}" class="btn" style="background: red">Cancel</a>
                    <input type="submit" value="Save" id="add-event" class="btn">
                </div>
            </form><!-- .billingForm -->
        </div><!-- .checkout-form -->
    </div>
    <div class="col-lg-2"></div>
</div>
@push('scripts')
<script>
    let eventLat = document.getElementById('event_lat').innerHTML
    let eventLong = document.getElementById('event_long').innerHTML

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

   function initMap() {
      var latLng = new google.maps.LatLng(eventLat, eventLong);
      var map = new google.maps.Map(document.getElementById('location-map'), {
         center: {lat: parseFloat(eventLat), lng: parseFloat(eventLong)},
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

    $('#event_type').on('change', function() {
       if($(this).val() == 'Other') {
          $('#custom-event-type-input').removeAttr('hidden')
       } else {
          $('#custom-event-type-input').attr('hidden', 'hidden')
       }
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

      //   $("#event_add").submit();
      $('#add-event').val('Please Wait...')
      $('#add-event').attr('disabled','disabled')
      $.ajax({
         method: "POST",
         dataType: "json",
         headers: {
            Accept: "application/json"
         },
         url: "{{ route('events.update', json_encode($event->id)) }}",
         data: new FormData(this),
         processData: false,
         cache: false,
         contentType: false,
         success: ({ redirectPath }) => {
               window.location.assign(redirectPath)
         },
         error: (response) => {
            if(response.status === 422) {
               let errors = response.responseJSON.errors;
               Object.keys(errors).forEach(function (key) {
                  $("#" + key + "Error").children("strong").text(errors[key][0]);
               });

               $('#add-event').val('Save Details')
               $('#add-event').removeAttr('disabled')
               toastr.options =
                     {
                        "closeButton" : true,
                        "progressBar" : true,
                        "positionClass" : "toast-bottom-right"
                     }
               toastr.error("Please check the data and try again");
            } else if (response.status === 400) {
               $('#add-event').val('Save Details')
               $('#add-event').removeAttr('disabled')
               toastr.options =
                     {
                        "closeButton" : true,
                        "progressBar" : true,
                        "positionClass" : "toast-bottom-right"
                     }
               toastr.error("Please check the data and try again");
            } else {
               $('#add-event').val('Save Details')
               $('#add-event').removeAttr('disabled')
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

