@extends('layouts.master')
@section('title', 'Vendor')
@section('css')
   <style>
      .field-check > .add_service {
         max-width: 85% !important;
      }
   </style>
@endsection
@section('content')
<main id="main" class="site-main listing-main">
   <div class="listing-nav">
      <div class="listing-menu nav-scroll">
         <ul>
            <li class="active"><a href="#genaral" title="Genaral"><span class="icon"><i class="la la-cog"></i></span><span>Genaral</span></a></li>
            <li><a href="#location" title="Location"><span class="icon"><i class="la la-map-marker"></i></span><span>Location</span></a></li>
            <li><a href="#contact" title="Contact info"><span class="icon"><i class="la la-phone"></i></span><span>Contact info</span></a></li>
            <li><a href="#media" title="Media"><span class="icon"><i class="la la-image"></i></span><span>Media</span></a></li>
         </ul>
      </div>
   </div><!-- .listing-nav -->
   <div class="listing-content">
      <div class="row">
         <div class="col-md-6 col-lg-6 col-sm-12">
            <h2>Add new company</h2>
         </div>
      </div>
      <form action="{{ route('vendor.create') }}" method="POST" class="upload-form" id="vendor-complete-form" enctype="multipart/form-data">
         @csrf
         <div class="listing-box" id="genaral">
            <h3>Genaral</h3>
            <div class="field-group field-input">
               <label for="company_name">Company Name</label>
               <input type="text" id="company_name" placeholder="What the name of your company" name="company_name" value="{{ old('company_name') }}">
               <span id="company_nameError">
                  <strong class="error-message"></strong>
               </span>
            </div>
            <br>
            <div class="field-group">
               <h4 class="label">Company Description</h4>
               <div id="editor">{{ strip_tags(old('company_description')) }}</div>
               <textarea name="company_description" id="company_description" hidden>{{ old('company_description') }}</textarea>
               <span id="company_descriptionError">
                  <strong class="error-message"></strong>
               </span>
            </div>
         </div><!-- .listing-box -->

         <div class="listing-box" id="location">
            <h3>Location</h3>
            <div class="field-group field-maps">
               <div class="field-inline">
                  <label for="pac-input">Search your business location/address*</label>
               </div>
               <div class="field-map">
                  <input id="place_id" type="hidden" name="place_id">
                  <input type="text" name="location_map" id="pac-input" placeholder="Search Place Here">
                  <div id="location-map"></div>
                  <span id="location_mapError">
                     <strong class="error-message"></strong>
                  </span>
               </div>
            </div>
         </div><!-- .listing-box -->
         <div class="listing-box" id="contact">
            <h3>Contact Info</h3>
            <div class="row">
               <div class="col-lg-6 col-md-6 col-sm-12">
                  <div class="field-group">
                     <label for="company_email">Email</label>
                     <input type="email" id="company_email" placeholder="Your email address" name="company_email" value="{{ old('company_email') }}">
                     <span id="company_emailError">
                        <strong class="error-message"></strong>
                     </span>
                  </div>
               </div>
               <div class="col-lg-6 col-md-6 col-sm-12">
                  <div class="field-group">
                     <label for="company_phone_number">Phone number</label>
                     <input type="tel" id="company_phone_number" placeholder="Your phone number" name="company_phone_number" value="{{ old('company_phone_number') }}">
                     <span id="company_phone_numberError">
                        <strong class="error-message"></strong>
                     </span>
                  </div>
               </div>
            </div>
         </div><!-- .listing-box -->

         <div class="listing-box" id="media">
            <h3>Media</h3>
            <div class="field-group field-file">
               <label>Company Logo</label>
               <label class="preview">
                  <input type="file" name="company_logo" class="upload-file" data-max-size="50000" value="{{ old('company_logo') }}" accept=".jpg,.jpeg,.png">
                  <img class="img_preview" src="{{ asset('assets/images/no-image.png') }}" alt="" />
                  <i class="la la-cloud-upload-alt"></i>
               </label>
               <div class="field-note">Maximum file size: 5 MB.</div>
               <span id="company_logoError">
                  <strong class="error-message"></strong>
               </span>
            </div>
         </div><!-- .listing-box -->

         <div class="listing-box" id="service">
            <div class="field-group field-check">
               <label for="add_service" class="add_service">
                  <input type="checkbox" name="add_service" id="add_service" value="add_service" {{ old('add_service') == 'add_service' ? 'checked' : '' }}  onchange="enterServiceDetails(this)">Add service details
                  <span class="checkmark">
                     <i class="la la-check"></i>
                  </span>
                  <div class="field-note">Select if you would like to enter your service details now?</div>
               </label>
            </div>
         </div>

         <div id="service-details" hidden>
            <h2>Service Details</h2>
            <p>Enter the details of the service here...</p>
            <div class="listing-box" id="genaral">
               <div class="field-inline">
                  <div class="field-group field-input">
                     <label for="company_name">Service Title</label>
                     <input type="text" id="service_title" placeholder="What the name of your the service" name="service_title" value="{{ old('service_title') }}">
                     <span id="service_titleError">
                        <strong class="error-message"></strong>
                     </span>
                  </div>
                  <div class="field-group field-input">
                     <label for="company_name">Service Category</label>
                     <select name="service_category" id="service_category">
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                           <option value="{{ $category->id }}" {{ old('service_category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                     </select>
                     <span id="service_categoryError">
                        <strong class="error-message"></strong>
                     </span>
                  </div>
               </div>
               <br>
               <div class="field-inline">
                  <div class="field-group">
                     <h4 class="label">Basic Details about the service you offer</h4>
                     <textarea name="service_description" class="form-control" style="height: 180px" id="service_description">{{ old('service_description') }}</textarea>
                     <span id="service_descriptionError">
                        <strong class="error-message"></strong>
                     </span>
                  </div>
                  <div class="field-group field-file">
                     <label>Service Cover Image</label>
                     <label class="preview">
                        <input type="file" name="service_cover_image" accept=".jpg,.png,.jpeg" class="upload-file" data-max-size="50000" value="{{ old('service_cover_image') }}">
                        <img class="img_preview" src="{{ asset('assets/images/no-image.png') }}" alt="" />
                        <i class="la la-cloud-upload-alt"></i>
                     </label>
                     <div class="field-note">Maximum file size: 5 MB.</div>
                     <span id="service_cover_imageError">
                        <strong class="error-message"></strong>
                     </span>
                  </div>
               </div>
            </div><!-- .listing-box -->

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
               <h3>Location</h3>
               <div class="field-group field-maps">
                  <div class="field-inline">
                     <label for="pac-input">Please enter the service location/address(Where the service is offered from)*</label>
                  </div>
                  <div class="field-map">
                     <input id="place_service_id" type="hidden" name="place_service_id">
                     <input type="text" name="service_location_map" id="pac-input-2">
                     <div id="service-location-map"></div>
                  </div>
               </div>
               <span id="service_location_mapError">
                  <strong class="error-message"></strong>
               </span>
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
                        <span id="service_contact_emailError">
                           <strong class="error-message"></strong>
                        </span>
                     </div>
                     <div class="field-group">
                        <label for="service_contact_phone_number">Phone number</label>
                        <input type="tel" id="service_contact_phone_number" placeholder="Your phone number" name="service_contact_phone_number" value="{{ old('service_contact_phone_number') }}">
                        <span id="service_contact_phone_numberError">
                           <strong class="error-message"></strong>
                        </span>
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

               {{-- <div class="listing-box">
                  <div class="field-group">
                     <h4 class="label">Pricing Package</h4>
                     <p>Enter Package Deals for this item</p>
                     <div id="pricing-package-items">
                        <div class="row pricing-package-item mb-1">
                           <div class="col-11">
                              <textarea name="service_packages[]" class="form-control" style="height: 50px" id="service_package"></textarea>
                           </div>
                           <div class="col-1" id="pricing-package-delete" style="cursor: pointer">
                              <i class="fas fa-trash"></i>
                           </div>
                        </div>
                     </div>
                  </div>
                  <span class="btn-sm" id="add-pricing-package-button" style="cursor: pointer">
                     <i class="fas fa-plus"></i>
                     Add Package
                  </span>
               </div> --}}
            </div>
         </div>
         <div class="field-group field-submit">
            <input type="submit" value="Submit" id="vendor-submit" class="btn">
         </div>
      </form>
   </div><!-- .listing-content -->
</main><!-- .site-main -->
@push('scripts')
   <script>
      window.onbeforeunload = function () {
         $('#vendor-submit').atrr('disabled', 'disabled').val('Saving...')
      }

      var mapInstance;
      var marker;
      var serviceMapInstance;
      var serviceMarker;

      function enterServiceDetails(value) {
         if (value.checked) {
            $('#service-details').removeAttr('hidden')
         } else {
            $('#service-details').attr('hidden', 'hidden')
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

      function companyInfo(value) {
         if (value.checked) {
            $('#service_contact_email').val($('#company_email').val())
            $('#service_contact_phone_number').val($('#company_phone_number').val())
         } else {
            $('#service_contact_email').val('')
            $('#service_contact_phone_number').val('')
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

      function placeServiceMarker(location) {
         if (serviceMarker) {
            serviceMarker.setPosition(location);
         } else {
            serviceMarker = new google.maps.Marker({
                  position: location,
                  map: serviceMapInstance
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
      //             style: google.maps.MapTypeControlStyle.DEFAULT
      //       }
      //    };
      //    var defaultBounds = new google.maps.LatLngBounds(new google.maps.LatLng(-1.270104, 36.80814));

      //    var input = document.getElementById('pac-input');
      //    var serviceInput = document.getElementById('pac-input-2');

      //    var searchBox = new google.maps.places.SearchBox(input, {
      //       bounds: defaultBounds
      //    });

      //    var searchBox2 = new google.maps.places.SearchBox(serviceInput, {
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

      //    searchBox2.addListener('places_changed', function() {
      //       var places = searchBox2.getPlaces();

      //       if (places.length == 0) {
      //             return;
      //       }

      //       markers = [];

      //       // Clear out the old markers.
      //       markers.forEach(function(marker) {
      //             serviceMarker.setMap(null);
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
      //                map: serviceMapInstance,
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
      //       serviceMapInstance.fitBounds(bounds);
      //    });

      //    mapInstance = new google.maps.Map(document.getElementById("location-map"), mapOptions);
      //    serviceMapInstance = new google.maps.Map(document.getElementById("service-location-map"), mapOptions);

      //    google.maps.event.addListener(mapInstance, 'click', function(event) {
      //       placeMarker(event.latLng);
      //    });

      //    google.maps.event.addListener(serviceMapInstance, 'click', function(event) {
      //       placeServiceMarker(event.latLng);
      //    })
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

         var service_map = new google.maps.Map(document.getElementById('service-location-map'), {
            center: {lat: -1.270104, lng: 36.80814},
            zoom: 13
         });

         var service_input = document.getElementById('pac-input-2');

         var service_autocomplete = new google.maps.places.Autocomplete(service_input);
         service_autocomplete.bindTo('bounds', map);

         var serviceInfoWindow = new google.maps.InfoWindow();

         serviceMarker = new google.maps.Marker({
            map: service_map,
            anchorPoint: new google.maps.Point(0, -29)
         });

         service_autocomplete.addListener('place_changed', function() {
            serviceInfoWindow.close();
            serviceMarker.setVisible(false);
            var place = service_autocomplete.getPlace();

            if (!place.geometry) {
               window.alert("Autocomplete's returned place contains no geometry");
               return;
            }

            document.getElementById('place_service_id').value = place.place_id

            service_map.setCenter(place.geometry.location);
            service_map.setZoom(17);

            placeServiceMarker(place.geometry.location);
            serviceMarker.setVisible(true);
         });

         google.maps.event.addListener(service_map, 'click', function(event) {
            geocoder.geocode({
               'location': event.latLng
            }, function(results, status) {
               if (status === google.maps.GeocoderStatus.OK) {
               if (results[0]) {
                  document.getElementById('place_service_id').value = results[0].place_id
               } else {
                  console.log('No results found');
               }
               } else {
                  console.log('Geocoder failed due to: ' + status);
               }
            });
            placeServiceMarker(event.latLng);
         });

      }

      $('#service_pricing_price').on('input', function() {
         var num = $(this).val().replace(/,/gi, "");
         var num2 = num.split(/(?=(?:\d{3})+$)/).join(",")
         $(this).val(num2)
      })

      $("#vendor-complete-form").on("submit", function(e) {
         // Prevent normal submit action
         e.preventDefault();
         // Collect current latlng of marker and put in hidden form field
         if (marker) {
            $("#location_map").val(marker.getPosition());
         } else {
            $("#location_map").val("");
         }
         if(serviceMarker) {
            $('#service_location_map').val(serviceMarker.getPosition());
         } else {
            $('#service_location_map').val("");
         }

         $('#vendor-submit').val('Please Wait...')
         $('#vendor-submit').attr('disabled', 'disabled')

         $.ajax({
            method: "POST",
            dataType: "json",
            headers: {
               Accept: "application/json"
            },
            url: "{{ route('vendor.create') }}",
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
                  toastr.options =
                        {
                           "closeButton" : true,
                           "progressBar" : true,
                           "positionClass" : "toast-bottom-right"
                        }
                  toastr.error("Please check the data and try again");
                  $("#vendor-submit").val('Submit')
                  $("#vendor-submit").removeAttr('disabled')
               } else if (response.status === 400) {
                  if (response.responseJSON.errors == "Invalid Service Price Value") {
                     $("#service_pricing_priceError").children("strong").text(response.responseJSON.errors);
                  }
                  toastr.options =
                        {
                           "closeButton" : true,
                           "progressBar" : true,
                           "positionClass" : "toast-bottom-right"
                        }
                  toastr.error("Please check the data and try again");
                  $("#vendor-submit").val('Submit')
                  $("#vendor-submit").removeAttr('disabled')
               } else {
                  $("#vendor-submit").val('Submit')
                  $("#vendor-submit").removeAttr('disabled')
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
@endsection
