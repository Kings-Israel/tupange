@extends('layouts.master')
@section('title', 'Edit Vendor Details')
@section('content')
<main id="main" class="site-main">
    <div class="container">
        <div class="col-md-7">
            <p id="vendor_lat" hidden>{{ $vendor->location_lat }}</p>
            <p id="vendor_long" hidden>{{ $vendor->location_long }}</p>
        </div>
        <div class="">
            <div class="listing-content">
                <h2>Edit Vendor Profile</h2>
                <form action="{{ route('vendor.profile.edit') }}" method="POST" class="" id="vendor-edit-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                    <div class="listing-box" id="genaral">
                        <div class="field-inline">
                            <div class="field-group field-input">
                                <label for="company_name">Company Name</label>
                                <input type="text" id="company_name" placeholder="What the name of your the service" name="company_name" value="{{ $vendor->company_name }}">
                                <x-input-error for="company_name"></x-input-error>
                            </div>
                            <div class="field-group field-input">
                                <label for="company_name">Company Phone Number</label>
                                <input type="text" id="company_phone_number" placeholder="Phone Number" name="company_phone_number" value="{{ $vendor->company_phone_number }}" readonly>
                                <x-input-error for="company_phone_number"></x-input-error>
                            </div>
                        </div>
                        <div class="field-group field-input">
                            <label for="company_name">Company Email</label>
                            <input type="text" id="company_email" placeholder="Email" name="company_email" value="{{ $vendor->company_email }}" readonly>
                            <x-input-error for="company_email"></x-input-error>
                        </div>
                        <br>
                        <div class="field-inline">
                            <div class="field-group">
                                <h4 class="label">Description</h4>
                                <textarea name="company_description" class="form-control" style="height: 180px" id="company_description">{{ $vendor->company_description }}</textarea>
                                <x-input-error for="company_description"></x-input-error>
                            </div>
                            <div class="field-group field-file">
                                <label for="company_logo">Company Logo</label>
                                <label for="company_logo" class="preview">
                                    <input type="file" id="company_logo" name="company_logo" accept=".jpg,.png,.jpeg" class="upload-file" data-max-size="50000" value="{{ $vendor->getCompanyLogo($vendor->company_logo) }}">
                                    <img class="img_preview" src="{{ $vendor->getCompanyLogo($vendor->company_logo) }}" alt="" />
                                    <i class="la la-cloud-upload-alt"></i>
                                </label>
                                <div class="field-note">Maximum file size: 5 MB.</div>
                                <x-input-error for="cmopany_logo"></x-input-error>
                            </div>
                        </div>
                    </div><!-- .listing-box -->

                    <div class="listing-box" id="location">
                        <h3>Location</h3>
                        <div class="field-group field-maps">
                            <div class="field-inline">
                                <label for="pac-input">Place Location at Google Map</label>
                            </div>
                            <div class="field-map">
                              <input id="place_id" type="hidden" name="place_id">
                              <input type="text" name="vendor_location_map" id="pac-input" placeholder="Search Place Here" value="{{ $vendor->location }}">
                              <div id="location-map"></div>
                           </div>
                           <x-input-error for="location_map" />
                        </div>
                        <x-input-error for="v_location"></x-input-error>
                    </div><!-- .listing-box -->
                    <div class="field-group field-submit d-flex justify-content-between">
                       <a href="{{ route('vendor.dashboard') }}" class="btn m-3" style="background-color: red" title="Cancel">Cancel</a>
                        <input type="submit" value="Submit" id="vendor-submit" class="btn">
                    </div>
                </form>
            </div><!-- .listing-content -->
        </div>
    </div>
</main>
@push('scripts')
<script>
   let lat = document.getElementById('vendor_lat').innerHTML
   let long = document.getElementById('vendor_long').innerHTML

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

   function initMap() {
      var latlng = new google.maps.LatLng(lat, long);
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
         position: latlng,
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

    $("#vendor-submit").on("click", function(e) {
        // Prevent normal submit action
        e.preventDefault();

        // Collect current latlng of marker and put in hidden form field
        if (marker) {
            $("#v_location").val(marker.getPosition());
        } else {
            $("#v_location").val(-1.270104, 36.80814);
        }

        $("#vendor-edit-form").submit();
    });
</script>
<script src={{ config('services.maps.key') }} async defer></script>
@endpush
@endsection
