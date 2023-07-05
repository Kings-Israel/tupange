@extends('layouts.master')
@section('title', 'Vendor')
@section('content')
<main id="main" class="site-main service-details">
   <div class="owner-page-wrap">
      <div class="container">
         <p id="vendor_location_lat" hidden>{{ $vendor->location_lat }}</p>
         <p id="vendor_location_long" hidden>{{ $vendor->location_long }}</p>
         <div class="row">
            <div class="col-lg-4">
               <div class="sidebar sidebar--shop sidebar--border">
                  <aside class="widget widget-shadow widget-reservation">
                        <div class="shop-details__thumb">
                           <a title="{{ $vendor->company_name }}" href="#">
                              <img src="{{ $vendor->getCompanyLogo($vendor->company_logo) }}" alt="{{ $vendor->company_name }}" style="border-radius: 5px;">
                           </a>
                        </div>
                        <br>
                        <h1>{{ $vendor->company_name }}</h1>
                        <h3>Successful Service Delivery Count: <span>{{ $vendor->orders_count }}</span></h3>
                        <div class="place__author">
                           <div class="place__author__info">
                              <div class="place__author__star">
                                 <i class="la la-star"></i>
                                 <i class="la la-star"></i>
                                 <i class="la la-star"></i>
                                 <i class="la la-star"></i>
                                 <i class="la la-star"></i>
                                 <span style="width: {{ ($vendorRating/5) * 100 }}%">
                                    <i class="la la-star"></i>
                                    <i class="la la-star"></i>
                                    <i class="la la-star"></i>
                                    <i class="la la-star"></i>
                                    <i class="la la-star"></i>
                                 </span>
                              </div>
                           </div>
                        </div>
                        <div class="place__box place__box-overview">
                           <h3>Description</h3>
                           <div class="place__desc">{{ $vendor->company_description }}.</div><!-- .place__desc -->

                           <div class="place__box place__box-map">
                              <h3 class="place__title--additional">
                                    Location
                              </h3>
                              <div class="address">
                                    <i class="la la-map-marker"></i>
                                    <span id="location"></span>
                              </div>
                                 <div class="maps">
                                    <div id="map"></div>
                              </div>
                           </div><!-- .place__box -->
                        </div>
                  </aside><!-- .widget-reservation -->
               </div><!-- .sidebar -->
            </div>
            <div class="col-lg-8">
               <div class="place__left">
                  <div class="place__box place__box--npd">
                     <div class="row">
                        <h1 class="col-lg-3">Services</h1>
                        <div class="row">
                           @if ($vendor->services->count())
                              @foreach ($vendor->services as $service)
                                 <div class="col-lg-5 col-md-6 col-sm-12 mb-2">
                                    <livewire:home-service-view :service="$service" />
                                 </div>
                              @endforeach
                           @else
                              <p>Services will be added soon...</p>
                           @endif
                        </div>
                     </div>
                  </div>
               </div><!-- .place__left -->
               <br>
            </div>
         </div>
      </div>
   </div>
</main><!-- .site-main -->
@push('scripts')
   <script>
      let vendor_location_lat = document.getElementById('vendor_location_lat').innerHTML
      let vendor_location_long = document.getElementById('vendor_location_long').innerHTML

      $.ajax({
         url: 'https://maps.googleapis.com/maps/api/geocode/json?latlng='+vendor_location_lat+','+vendor_location_long+'&key=AIzaSyCisnVFSnc5QVfU2Jm2W3oRLqMDrKwOEoM',
         type: 'GET',
         success: function(result) {
            document.getElementById('location').innerHTML = result.results[6].formatted_address
         },
         error: function(error) {
            console.log(error);
         }
      })

      let locationOnMap = document.getElementById('location')

    var mapInstance;
    var marker;

    function initMap() {
        var latlng = new google.maps.LatLng(vendor_location_lat, vendor_location_long);
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
        mapInstance = new google.maps.Map(document.getElementById("map"), mapOptions);
        marker = new google.maps.Marker({
            position: latlng,
            map: mapInstance,
            draggable: false
        })
    }
   </script>
   <script src={{ config('services.maps.key') }} async defer></script>
@endpush
@endsection
