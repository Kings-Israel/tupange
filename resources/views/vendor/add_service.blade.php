@extends('layouts.master')
@section('title', 'Add Service')
@section('content')
<main id="main" class="site-main">
   <div class="container">
      <div class="">
         <div class="listing-content">
               <h2>Add new service</h2>
               <p>These are the basic details about the service you are selling. Service pricing, gallery, rating & reviews among other additional details are available on the service profile.</p>
               <form action="{{ route('vendor.service.add') }}" method="POST" class="" id="service-add-form" enctype="multipart/form-data">
                  @csrf
                  <div class="listing-box" id="genaral">
                     <div class="field-inline">
                           <div class="field-group field-input">
                              <label for="company_name">Service Title</label>
                              <input type="text" id="service_title" placeholder="What the name of your the service" name="service_title" value="{{ old('service_title') }}">
                              <x-input-error for="service_title"></x-input-error>
                           </div>
                           <div class="field-group field-input">
                              <label for="company_name">Service Category</label>
                              <select name="service_category" id="service_category">
                                 <option value="">Select Category</option>
                                 @foreach ($categories as $category)
                                       <option value="{{ $category->id }}" {{ old('service_category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                 @endforeach
                              </select>
                              <x-input-error for="service_category"></x-input-error>
                           </div>
                     </div>
                     <br>
                     <div class="field-inline">
                           <div class="field-group">
                              <h4 class="label">Basic Details about the service you offer</h4>
                              <textarea name="service_description" class="form-control" style="height: 180px" id="service_description">{{ old('service_description') }}</textarea>
                              <x-input-error for="service_description"></x-input-error>
                           </div>
                           <div class="field-group field-file">
                              <label for="company_logo">Service Cover Image</label>
                              <label for="company_logo" class="preview">
                                 <input type="file" id="company_logo" name="service_cover_image" accept=".jpg,.png,.jpeg" class="upload-file" data-max-size="50000" value="{{ old('service_cover_image') }}">
                                 <img class="img_preview" src="{{ asset('assets/images/no-image.png') }}" alt="" />
                                 <i class="la la-cloud-upload-alt"></i>
                              </label>
                              <div class="field-note">Maximum file size: 5 MB.</div>
                              <x-input-error for="service_cover_image"></x-input-error>
                           </div>
                     </div>
                  </div><!-- .listing-box -->

                  <div class="listing-box" id="location">
                     <h3>Location</h3>
                     <div class="field-group field-maps">
                           <div class="field-inline">
                              <label for="pac-input">Please enter the service location/address(Where the service is offered from)*</label>
                           </div>
                           <div class="field-map">
                              <input name="place_id" id="place_id" type="hidden">
                              <input type="text" name="service_location_map" id="pac-input" placeholder="Search place to find on map">
                              <div id="location-map"></div>
                           </div>
                     </div>
                     <x-input-error for="service_location_map"></x-input-error>
                     <x-input-error for="place_id"></x-input-error>
                  </div><!-- .listing-box -->
                  <div class="field-inline">
                     <div class="listing-box" id="contact">
                           <h3>Contact Info</h3>
                           <div class="field-group field-check">
                              <label for="company_contacts">
                                 <input type="checkbox" name="use_company_contacts" id="company_contacts" value="Use-Company-Contacts" onchange="companyInfo(this)">Use Company Contact Information
                                 <span class="checkmark">
                                       <i class="la la-check"></i>
                                 </span>
                              </label>
                           </div>
                           <div class="field-inline">
                              <div class="field-group">
                                 <label for="service_contact_email">Email</label>
                                 <input type="email" id="service_contact_email" placeholder="Your email address" name="service_contact_email" value="{{ old('service_contact_email') }}">
                                 <x-input-error for="service_contact_email"></x-input-error>
                              </div>
                              <div class="field-group">
                                 <label for="service_contact_phone_number">Phone number</label>
                                 <input type="tel" id="service_contact_phone_number" placeholder="Your phone number" name="service_contact_phone_number" value="{{ old('service_contact_phone_number') }}">
                                 <x-input-error for="service_contact_phone_number"></x-input-error>
                              </div>
                           </div>
                     </div><!-- .listing-box -->
                  </div>
                  <div class="listing-box">
                     <h3>Service Gallery</h3>
                     <p>Upload Images to the service's gallery...</p>
                     <div class="field-note">Maximum file size: 5 MB.</div>
                     <div class="listing-box" id="genaral">
                        <div class="row">
                           <div class="col-sm-6 col-md-6 col-lg-3">
                              <div class="field-group field-file">
                                 <label class="preview">
                                    <input type="file" name="service_gallery[0]" accept=".jpg,.png,.jpeg" class="upload-file" data-max-size="50000" value="{{ old('service_gallery[0]') }}">
                                    <img class="img_preview" src="{{ asset('assets/images/no-image.png') }}" alt="" />
                                    <i class="la la-cloud-upload-alt"></i>
                                 </label>
                              </div>
                           </div>
                           <div class="col-sm-6 col-md-6 col-lg-3">
                              <div class="field-group field-file">
                                 <label class="preview">
                                    <input type="file" name="service_gallery[1]" accept=".jpg,.png,.jpeg" class="upload-file" data-max-size="50000" value="{{ old('service_gallery[1]') }}">
                                    <img class="img_preview" src="{{ asset('assets/images/no-image.png') }}" alt="" />
                                    <i class="la la-cloud-upload-alt"></i>
                                 </label>
                              </div>
                           </div>
                           <div class="col-sm-6 col-md-6 col-lg-3">
                              <div class="field-group field-file">
                                 <label class="preview">
                                    <input type="file" name="service_gallery[2]" accept=".jpg,.png,.jpeg" class="upload-file" data-max-size="50000" value="{{ old('service_gallery[2]') }}">
                                    <img class="img_preview" src="{{ asset('assets/images/no-image.png') }}" alt="" />
                                    <i class="la la-cloud-upload-alt"></i>
                                 </label>
                              </div>
                           </div>
                           <div class="col-sm-6 col-md-6 col-lg-3">
                              <div class="field-group field-file">
                                 <label class="preview">
                                    <input type="file" name="service_gallery[3]" accept=".jpg,.png,.jpeg" class="upload-file" data-max-size="50000" value="{{ old('service_gallery[3]') }}">
                                    <img class="img_preview" src="{{ asset('assets/images/no-image.png') }}" alt="" />
                                    <i class="la la-cloud-upload-alt"></i>
                                 </label>
                              </div>
                           </div>
                           <div class="col-sm-6 col-md-6 col-lg-3">
                              <div class="field-group field-file">
                                 <label class="preview">
                                    <input type="file" name="service_gallery[4]" accept=".jpg,.png,.jpeg" class="upload-file" data-max-size="50000" value="{{ old('service_gallery[4]') }}">
                                    <img class="img_preview" src="{{ asset('assets/images/no-image.png') }}" alt="" />
                                    <i class="la la-cloud-upload-alt"></i>
                                 </label>
                              </div>
                           </div>
                        </div>
                     </div><!-- .listing-box -->
                  </div>
                  <div class="listing-box">
                     <div class="field-group field-check">
                        <label for="add_service_pricing">
                           <input type="checkbox" name="add_service_pricing" id="add_service_pricing" value="add_service_pricing" {{ old('add_service_pricing') == 'add_service_pricing' ? 'checked' : '' }} onchange="addPricingPackage(this)">Add a service pricing for this service
                           <span class="checkmark">
                              <i class="la la-check"></i>
                           </span>
                           <div class="field-note">Select if you would like to add pricing for this service</div>
                        </label>
                     </div>
                  </div><!-- .listing-box -->

                  <div id="service-pricing" hidden>
                     <h2>Add a Pricing Package</h2>
                     <div class="field-inline">
                        <div class="listing-box">
                           <div class="row">
                              <div class="col-lg-6 col-md-6 col-sm-12">
                                 <div class="field-input">
                                    <input type="text" placeholder="Title" value="{{ old('service_pricing_title') }}" class="form-control" name="service_pricing_title">
                                    <span id="service_pricing_titleError">
                                       <strong class="error-message"></strong>
                                    </span>
                                 </div>
                              </div>
                              <div class="col-lg-6 col-md-6 col-sm-12">
                                 <div class="field-input">
                                    <input type="text" placeholder="Price (Ksh.)" min="1" value="{{ old('service_pricing_price') }}" class="form-control" id="service_pricing_price" name="service_pricing_price" autocomplete="off">
                                    <span id="service_pricing_priceError">
                                       <strong class="error-message"></strong>
                                    </span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="listing-box">
                        <div class="field-group">
                           <h4 class="label">Description</h4>
                           <textarea name="service_pricing_description" class="form-control" style="height: 80px" id="service_pricing_description">{{ old('service_pricing_description') }}</textarea>
                           <span id="service_pricing_descriptionError">
                              <strong class="error-message"></strong>
                           </span>
                        </div>
                     </div>
                  </div>
                  <div class="field-group field-submit">
                     <input type="hidden" name="action" id="action">
                     <input type="submit" value="Submit" id="vendor-submit" class="btn">
                     <input type="submit" value="Submit And Add" id="vendor-submit-and-add" class="btn">
                     <a href="{{ route('vendor.services.all') }}" class="m-3" title="Cancel">Cancel</a>
                  </div>
               </form>
         </div><!-- .listing-content -->
      </div>
   </div>
</main>
@push('scripts')
   <script>
      window.onbeforeunload = function () {
         $('#vendor-submit-and-add').attr('disabled', 'disabled').val('Saving...')
         $('#vendor-submit').attr('disabled', 'disabled').val('Saving...')
       }

      $vendor = {!! json_encode($vendor->toArray()) !!}
      function companyInfo(value) {
         if (value.checked) {
            $('#service_contact_email').val($vendor.company_email)
            $('#service_contact_phone_number').val('0'+$vendor.company_phone_number)
         } else {
            $('#service_contact_email').val('')
            $('#service_contact_phone_number').val('')
         }
      }

      function addPricingPackage(value) {
         if (value.checked) {
            $('#service-pricing').removeAttr('hidden')
         } else {
            $('#service-pricing').attr('hidden', 'hidden')
         }
      }

      let pricing_package_items = $("#pricing-package-items")

      $(document.body).on('click', "#add-pricing-package-button", function(e) {
         e.preventDefault()
         let pricing_package_html = '<div class="row pricing-package-item mb-1">'
            pricing_package_html += '<div class="col-11"><textarea name="service_packages[]" class="form-control" style="height: 50px" id="service_package"></textarea></div>'
            pricing_package_html += '<div class="col-1" id="pricing-package-delete" style="cursor: pointer"><i class="fas fa-trash"></i></div>'
            pricing_package_html += '</div>'

         $(pricing_package_html).appendTo(pricing_package_items)
      })

      $(document.body).on("click", "#pricing-package-delete", function(event){
         let $btn = $(this);
         let $item = $btn.parent();
         if (pricing_package_items.children().length > 1) $item.remove();
      });

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
      //    var latlng = new google.maps.LatLng(-1.270104, 36.80814);
      //    var mapOptions = {
      //       zoom: 15,
      //       center: latlng,
      //       mapTypeId: google.maps.MapTypeId.ROADMAP,
      //       mapTypeControlOptions: {
      //          style: google.maps.MapTypeControlStyle.DEFAULT
      //       }
      //    };

      //    var defaultBounds = new google.maps.LatLngBounds(new google.maps.LatLng(-1.270104, 36.80814));

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

      $("#vendor-submit").on("click", function(e) {
         // Prevent normal submit action
         e.preventDefault();

         // Collect current latlng of marker and put in hidden form field
         if (marker) {
            $("#service_location_map").val(marker.getPosition());
         } else {
            $("#service_location_map").val("(-1.270104, 36.80814)");
         }

         $('#action').val('Submit')
         $("#service-add-form").submit();
      });

      $("#vendor-submit-and-add").on("click", function(e) {
         // Prevent normal submit action
         e.preventDefault();

         // Collect current latlng of marker and put in hidden form field
         if (marker) {
            $("#service_location_map").val(marker.getPosition());
         } else {
            $("#service_location_map").val("(-1.270104, 36.80814)");
         }

         $('#action').val('Submit and Add')
         $("#service-add-form").submit();
      });
   </script>
   <script src={{ config('services.maps.key') }} async defer></script>
@endpush
@endsection
