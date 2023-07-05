@extends('layouts.master')
@section('title', 'Service')
@section('css')
   <style>
      .service-image-close {
         position: absolute;
         top: 10px;
         right: 10px;
         z-index: 9;
         color: white;
      }
      .modal-content .service-image-description {
         margin: 20px;
      }
      .add-service-cart-btn {
        padding: 2px 4px;
      }
      .in-cart-btn {
        color: rgb(36, 36, 36);
      }
      .pricing-actions {
         background-color: #cccccc;
      }
   </style>
@endsection
@section('content')
<main id="main" class="site-main service-details">
   @include('partials.add-service-package')
   @include('partials.add-service-images')
   @include('partials.pause-service')

   <div class="owner-page-wrap">
      <div class="container">
         <div class="col-md-7">
               <p id="service_lat" hidden>{{ $service->service_location_lat }}</p>
               <p id="service_long" hidden>{{ $service->service_location_long }}</p>
         </div>

         <div class="container">
               <div class="row">
                  <div class="col-lg-4">
                     <div class="sidebar sidebar--shop sidebar--border">
                        <aside class="widget widget-shadow widget-reservation">
                           <h1>{{ $service->service_title }}</h1>
                              <div class="shop-details__thumb">
                                 <a title="{{ $service->service_title }}" href="#"><img src="{{ $service->getCoverImage($service->service_image) }}" alt="{{ $service->service_title }}" onerror="this.onerror=null; this.src='{{ $service->vendor->getCompanyLogo($service->vendor->company_logo) }}'"></a>
                              </div>
                              <br>
                              <h4 class="mb-3 service-{{ $service->getServiceStatus($service->service_status_id)->name }}">
                                 {{ $service->getServiceStatus($service->service_status_id)->name }}
                              </h4>
                              @if ($service->service_status_id == 2 && $service->pause_until != null)
                                 <h5 class="mb-1">
                                    Paused upto {{ Carbon\Carbon::parse($service->pause_until)->format('M d, Y') }}
                                 </h5>
                              @endif
                              <div class="row">
                                 <div class="col-sm-12 col-md-6">
                                    <div class="right-header__button btn">
                                       <a title="Edit Service" class="w-100" href="{{ route('vendor.service.edit.view', $service) }}">
                                          <span>Edit Service</span>
                                       </a>
                                    </div>
                                 </div>
                                 <div class="col-sm-12 col-md-6" id="vendor-service-update-section">
                                    @if ($service->service_status_id == 1)
                                       <button type="submit" data-bs-toggle="modal" data-bs-target="#pause-service-{{ $service->id }}" class="btn w-100 service-btn-{{ $service->getServiceStatus($service->service_status_id)->name }}">Pause</button>
                                    @elseif ($service->service_status_id == 2 || $service->service_status_id == 3)
                                       <form action="{{ route('vendor.service.resume') }}" method="post">
                                          @csrf
                                          <input type="hidden" name="service_id" value="{{ $service->id }}">
                                          <button type="submit" class="btn w-100 service-btn-{{ $service->getServiceStatus($service->service_status_id)->name }}">Resume</button>
                                       </form>
                                    @endif
                                 </div>
                              </div>
                              <br>
                              @if (($service->service_status_id == 2 || $service->service_status_id == 3) && $service->pause_note != null)
                                 <h5>Pause Note</h5>
                                 <p>{{ $service->pause_note }}</p>
                              @endif
                              <div class="place__box place__box-overview" style="text-align: left">
                                 <h3>Description</h3>
                                 <div class="place__desc">{{ $service->service_description }}.</div><!-- .place__desc -->

                                 <div class="place__box place__box-map">
                                       <h3 class="place__title--additional">
                                          Location
                                       </h3>
                                       <div class="address">
                                          <i class="la la-map-marker"></i>
                                          <span id="location">{{ $service->service_location }}</span>
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
                                 <h1 class="col-lg-3">Pricing</h1>
                                 <button class="btn col-lg-3" data-bs-toggle="modal" data-bs-target="#add-service-package">Add Pricing Package</button>
                              </div>
                              <livewire:pricings-index :service="$service" />
                           </div>
                     </div><!-- .place__left -->
                     <br>
                     <div class="place__left">
                           <div class="place__box place__box--npd">
                              <div class="row" >
                                 <h1 class="col-lg-3">Gallery</h1>
                                 @if (count($service->service_images) < 5)
                                    <div class="right-header__button btn btn-info col-lg-3" data-bs-toggle="modal" data-bs-target="#service-images-upload-{{ $service->id }}">
                                          <a title="Add Images" id="add-images" href="#">
                                             <span>Add Images</span>
                                          </a>
                                    </div>
                                 @endif
                              </div>
                              <div class="row">
                                 @if (count($service->service_images))
                                       @foreach ($service->service_images as $image)
                                          @include('partials.view-image')
                                          <div class="col-lg-4 col-md-6">
                                             <div class="place-item layout-02 place-hover">
                                                   <div class="place-inner">
                                                      <div class="place-thumb hover-img">
                                                         <a class="entry-thumb" data-bs-toggle="modal" data-bs-target="#view-image-{{ $image->id }}"><img src="{{ $service->getImage($image->image) }}" alt="{{ $service->service_title }}" onerror="this.onerror=null; this.src='{{ $service->vendor->getCompanyLogo($service->vendor->company_logo) }}'"></a>
                                                      </div>
                                                   </div>
                                             </div>
                                          </div>
                                       @endforeach
                                 @else
                                       <p>No Images added for this service</p>
                                 @endif
                              </div>
                           </div>
                           <livewire:reviews-view :service="$service" />
                     </div><!-- .place__left -->
                  </div>
               </div>
         </div>
      </div>
   </div>
</main><!-- .site-main -->
@push('scripts')
<script>
   let lat = document.getElementById('service_lat').innerHTML
   let long = document.getElementById('service_long').innerHTML
   let locationOnMap = document.getElementById('location')

   var mapInstance;
   var marker;

   function initMap() {
      var latlng = new google.maps.LatLng(lat, long);
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

   $(document.body).on('click', '#add-package', function (e) {
      e.preventDefault();
      $(this).parents('.service-details').find('#add-service-package').fadeIn(0);
      $(this).parents('.service-details').find('.popup-background').fadeIn(0);
      $(this).parents('.service-details').find('.popup-form').toggleClass('open');0
   });

   $('.popup__close').on('click', function (e) {
      e.preventDefault();
      $(this).parents('.service-details').find('.popup-background').fadeOut();
      $(this).parent().removeClass('open');
   });

   $('.popup-background').on('click', function (e) {
      e.preventDefault();
      $(this).fadeOut();
      $(this).parents('.service-details').find('.popup').removeClass('open');
   });

   let pricing_package_items = $("#pricing-package-items")

   $(document.body).on('click', "#add-pricing-package-button", function(e) {
      e.preventDefault()
      let pricing_package_html = '<div class="row pricing-package-item mb-1">'
         pricing_package_html += '<div class="col-11"><textarea name="service_packages[]" class="form-control" style="height: 50px"></textarea></div>'
         pricing_package_html += '<div class="col-1" id="pricing-package-delete" style="cursor: pointer"><i class="fas fa-trash"></i></div>'
         pricing_package_html += '</div>'

      $(pricing_package_html).appendTo(pricing_package_items)
   })

   $(document.body).on("click", "#pricing-package-delete", function(event){
      let $btn = $(this);
      let $item = $btn.parent();
      if (pricing_package_items.children().length > 1) $item.remove();
   });

</script>
<script src={{ config('services.maps.key') }} async defer></script>
@endpush
@endsection
