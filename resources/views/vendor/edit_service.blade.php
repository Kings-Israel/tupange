@extends('layouts.master')
@section('title', 'Edit Service')
@section('css')
   <style>
      #vendor-service-update-section {
         width: 40%;
      }
      .edit-service-section-header {
         display: flex;
         justify-content: space-between;
         margin-bottom: 5px;
      }
      .pricing-section-header {
         display: flex;
         justify-content: space-between;
         margin-bottom: 5px;
      }
      .pricing-actions {
         background-color: #cccccc;
      }
      .gallery-section-header {
         display: flex;
         justify-content: space-between;
         margin-bottom: 5px;
      }
      #pause-note {
         background: rgb(213, 213, 213);
         padding: 5px;
         margin: 0 10px 0 10px;
         border: 1px solid #000;
         border-radius: 3px;
         max-width: 400px;
      }
      #pause-note-mobile {
         display: none;
      }
      #submit-buttons-section {
         display: flex;
         justify-content: space-between;
      }
      @media screen and (max-width: 768px){
         .edit-service {
            margin-top: 30px;
         }
         #pause-note {
            display: none;
         }
         #pause-note-mobile {
            display: block;
            background: rgb(213, 213, 213);
            padding: 5px;
            border: 1px solid #000;
            border-radius: 3px;
         }
         .edit-service-section-header {
            margin: 20px -20px 10px -20px;
         }
         .pricing-section-header {
            margin: 10px -20px 10px -20px;
         }
         #service-edit-form {
            margin: 0 -50px 0 -50px;
         }
         .pricing-section {
            margin: 0 -50px 0 -50px;
         }
         .gallery-section {
            margin: 0 -50px 0 -50px;
         }
         #vendor-service-update-section {
            width: 70%;
         }
      }
      @media screen and (max-width: 575px){
         .edit-service-section-header {
            display: flex;
            flex-direction: column;
            width: 100%;
         }
         #vendor-service-update-section {
            width: 100%;
         }
      }
   </style>
@endsection
@section('content')
<main id="main" class="site-main">
   @include('partials.add-service-package')
   @include('partials.add-service-images')
   @include('partials.pause-service')
   @include('partials.delete-service')
    <div class="container">
        <div class="col-md-7">
            <p id="service_lat" hidden>{{ $service->service_location_lat }}</p>
            <p id="service_long" hidden>{{ $service->service_location_long }}</p>
        </div>
        <div class="edit-service">
            <div class="listing-content">
               <div class="edit-service-section-header">
                  <h1>Edit Service</h1>
                  @if ($service->getServiceStatus($service->service_status_id)->name === 'Paused' && $service->pause_note != NULL)
                     <div id="pause-note">
                        <h4>Pause Note</h4>
                        <p>{{ $service->pause_note }}</p>
                     </div>
                  @endif
                  <div id="vendor-service-update-section">
                    @if ($service->getServiceStatus($service->service_status_id)->name === 'Active')
                       <button type="submit" data-bs-toggle="modal" data-bs-target="#pause-service-{{ $service->id }}" class="btn service-btn-{{ $service->getServiceStatus($service->service_status_id)->name }}">Pause Service</button>
                    @elseif ($service->getServiceStatus($service->service_status_id)->name === 'Paused' || $service->getServiceStatus($service->service_status_id)->name === 'Deleted')
                     <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                           <button type="submit" data-bs-toggle="modal" data-bs-target="#delete-service-{{ $service->id }}" style="background-color: red" class="btn service-btn-{{ $service->getServiceStatus($service->service_status_id)->name }}">Delete Permanently</button>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                           <form action="{{ route('vendor.service.resume') }}" method="post">
                              @csrf
                              <input type="hidden" name="service_id" value="{{ $service->id }}">
                              <button type="submit" class="btn service-btn-{{ $service->getServiceStatus($service->service_status_id)->name }}">Resume Service</button>
                           </form>
                        </div>
                     </div>
                    @endif
                  </div>
               </div>
               @if ($service->service_status_id == 2 && $service->pause_note != NULL)
                  <div id="pause-note-mobile">
                     <h4>Pause Note</h4>
                     <p>{{ $service->pause_note }}</p>
                  </div>
               @endif
                <form action="{{ route('vendor.service.edit') }}" method="POST" class="" id="service-edit-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="service_id" value="{{ $service->id }}">
                    <div class="listing-box" id="genaral">
                        <div class="field-inline">
                            <div class="field-group field-input">
                                <label for="company_name">Service Title</label>
                                <input type="text" id="service_title" placeholder="What the name of your the service" name="service_title" value="{{ $service->service_title }}">
                                <x-input-error for="service_title"></x-input-error>
                            </div>
                            <div class="field-group field-input">
                                <label for="company_name">Service Category</label>
                                <select name="service_category" id="service_category">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $service->category_id == $category->id ? "selected" : "" }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error for="service_category"></x-input-error>
                            </div>
                        </div>
                        <br>
                        <div class="field-inline">
                            <div class="field-group">
                                <h4 class="label">Description</h4>
                                <textarea name="service_description" class="form-control" style="height: 180px" id="service_description">{{ $service->service_description }}</textarea>
                                <x-input-error for="service_description"></x-input-error>
                            </div>
                            <div class="field-group field-file">
                                <label for="company_logo">Service Cover Image</label>
                                <label for="company_logo" class="preview">
                                    <input type="file" id="company_logo" name="service_cover_image" accept=".jpg,.png,.jpeg" class="upload-file" data-max-size="50000" value="{{ $service->getCoverImage($service->service_image) }}">
                                    <img class="img_preview" src="{{ $service->getCoverImage($service->service_image) }}" alt="" />
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
                                 <input id="place_id" type="hidden" name="place_id">
                                 <input type="text" name="service_location_map" id="pac-input" value="{{ $service->service_location }}">
                                 <div id="location-map"></div>
                            </div>
                        </div>
                        <x-input-error for="service_location_map"></x-input-error>
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
                                    <input type="email" id="service_contact_email" placeholder="Your email address" name="service_contact_email" value="{{ $service->service_contact_email }}">
                                    <x-input-error for="service_contact_email"></x-input-error>
                                </div>
                                <div class="field-group">
                                    <label for="service_contact_phone_number">Phone number</label>
                                    <input type="tel" id="service_contact_phone_number" placeholder="Your phone number" name="service_contact_phone_number" value="{{ $service->service_contact_phone_number }}">
                                    <x-input-error for="service_contact_phone_number"></x-input-error>
                                </div>
                            </div>
                        </div><!-- .listing-box -->
                    </div>
                    <input type="hidden" name="action" id="action">
                    <div class="field-group field-submit d-flex justify-content-between">
                       <a href="{{ route('vendor.services.all') }}" class="btn" style="background: red" title="Cancel">Cancel</a>
                       <input type="submit" value="Submit" id="vendor-submit" class="btn">
                    </div>
                </form>
                <hr>
                <div class="pricing-section-header">
                   <h2>Pricing</h2>
                   <button class="btn" id="add-package-btn" data-bs-toggle="modal" data-bs-target="#add-service-package">Add Pricing Package</button>
                </div>
                <div class="listing-box pricing-section" style="border-radius: 8px; background-color: #e0e0e0">
                  <div class="place__box place__box--npd">
                     <livewire:pricings-index :service="$service" />
                  </div>
                </div>
                <hr>
                <div class="gallery-section-header">
                   <h2>Gallery</h2>
                   @if (count($service->service_images) < 5)
                      <div class="right-header__button btn col-lg-3" data-bs-toggle="modal" data-bs-target="#service-images-upload-{{ $service->id }}">
                            <a title="Add Images" id="add-images" href="#">
                               <span>Add Images</span>
                            </a>
                      </div>
                   @endif
                </div>
                <div class="listing-box gallery-section" style="border-radius: 8px; background-color: #e0e0e0">
                   <div class="place__box place__box--npd">
                     <div class="row">
                        @if (count($service->service_images))
                              @foreach ($service->service_images as $image)
                                 @include('partials.view-image')
                                 <div class="col-lg-4 col-md-6">
                                    <div class="place-item layout-02 place-hover mt-3">
                                          <div class="place-inner">
                                             <div class="place-thumb hover-img">
                                                <a class="entry-thumb" data-bs-toggle="modal" data-bs-target="#view-image-{{ $image->id }}"><img src="{{ $service->getImage($image->image) }}" alt=""></a>
                                             </div>
                                          </div>
                                    </div>
                                 </div>
                              @endforeach
                        @else
                              <p style="text-align: center">No Images added for this service</p>
                        @endif
                     </div>
                  </div>
                </div>
            </div><!-- .listing-content -->
        </div>
    </div>
</main>
@push('scripts')
<script>
   window.onbeforeunload = function () {
      $('#vendor-submit').attr('disabled', 'disabled').val('Updating...')
    }

   let lat = document.getElementById('service_lat').innerHTML
   let long = document.getElementById('service_long').innerHTML

   var mapInstance;
   var marker;

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

   //  function initMap() {
   //      var latlng = new google.maps.LatLng(lat, long);
   //      var mapOptions = {
   //          zoom: 15,
   //          center: latlng,
   //          mapTypeId: google.maps.MapTypeId.ROADMAP,
   //          mapTypeControlOptions: {
   //              style: google.maps.MapTypeControlStyle.DEFAULT
   //          }
   //      };
   //     var defaultBounds = new google.maps.LatLngBounds(new google.maps.LatLng(-1.270104, 36.80814));

   //     var input = document.getElementById('pac-input');

   //     var searchBox = new google.maps.places.SearchBox(input, {
   //        bounds: defaultBounds
   //     });

   //     searchBox.addListener('places_changed', function() {
   //        var places = searchBox.getPlaces();

   //        if (places.length == 0) {
   //           return;
   //        }

   //        markers = [];

   //        // Clear out the old markers.
   //        markers.forEach(function(marker) {
   //           marker.setMap(null);
   //        });

   //        // For each place, get the icon, name and location.
   //        var bounds = new google.maps.LatLngBounds();
   //        places.forEach(function(place) {
   //           if (!place.geometry) {
   //              console.log("Returned place contains no geometry");
   //              return;
   //           }
   //           var icon = {
   //              url: place.icon,
   //              size: new google.maps.Size(71, 71),
   //              origin: new google.maps.Point(0, 0),
   //              anchor: new google.maps.Point(17, 34),
   //              scaledSize: new google.maps.Size(25, 25)
   //           };

   //           // Create a marker for each place.
   //           markers.push(new google.maps.Marker({
   //              map: mapInstance,
   //              icon: icon,
   //              title: place.name,
   //              position: place.geometry.location
   //           }));

   //           if (place.geometry.viewport) {
   //              // Only geocodes have viewport.
   //              bounds.union(place.geometry.viewport);
   //           } else {
   //              bounds.extend(place.geometry.location);
   //           }
   //        });
   //        mapInstance.fitBounds(bounds);
   //     });
   //      mapInstance = new google.maps.Map(document.getElementById("location-map"), mapOptions);
   //      marker = new google.maps.Marker({
   //          position: latlng,
   //          map: mapInstance,
   //          draggable: false
   //      })

   //      google.maps.event.addListener(mapInstance, 'click', function(event) {
   //          placeMarker(event.latLng);
   //      });
   //  }

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

    $("#vendor-submit").on("click", function(e) {
        // Prevent normal submit action
        e.preventDefault();

        // Collect current latlng of marker and put in hidden form field
        if (marker) {
            $("#service_location_map").val(marker.getPosition());
        } else {
            $("#service_location_map").val("(-1.270104, 36.80814)");
        }

        $("#service-edit-form").submit();
    });
</script>
<script src={{ config('services.maps.key') }} async defer></script>
@endpush
@endsection
