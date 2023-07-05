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
      .shop-details__thumb > a > img {
         border-radius: 5px;
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
      @media only screen and (min-width: 900px) {
         .pricing-details {
            margin-left: 18px;
         }
         .gallery {
            margin-left: 18px;
         }
      }
   </style>
@endsection
@section('content')
<main id="main" class="site-main service-details">
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
                                <div class="shop-details__thumb">
                                    <a title="{{ $service->service_title }}" href="#">
                                       <img src="{{ $service->getCoverImage($service->service_image) }}" alt="{{ $service->service_title }}" onerror="this.onerror=null; this.src='{{ $service->vendor->getCompanyLogo($service->vendor->company_logo) }}'">
                                    </a>
                                </div>
                                <br>
                                <h1 style="text-align: left">{{ $service->service_title }}</h1>
                                <h4 style="text-align: left">Category: <strong>{{ $service->category->name }}</strong></h4>
                                <div class="place__box place__box-overview">
                                    <h3 style="text-align: left">Description</h3>
                                    <div class="place__desc" style="text-align: left">{{ $service->service_description }}.</div><!-- .place__desc -->

                                    <div class="place__box place__box-map">
                                        <h3 class="place__title--additional" style="text-align: left">
                                            Location
                                        </h3>
                                        <div class="address" style="text-align: left">
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
                                    @auth
                                        @if (auth()->user()->id != $service->vendor->user_id)
                                            <livewire:get-custom-quote :service="$service" />
                                        @endif
                                    @endauth
                                </div>
                                <livewire:pricings-index :service="$service" />
                            </div>
                        </div><!-- .place__left -->
                        <br>
                        <div class="place__left">
                            <div class="place__box place__box--npd">
                                <div class="row" >
                                    <h1 class="col-lg-3">Gallery</h1>
                                </div>
                                <div class="row">
                                    @if (count($service->service_images))
                                        @foreach ($service->service_images as $image)
                                            @include('partials.view-image')
                                            <div class="col-lg-4 col-md-6">
                                                <div class="place-item layout-02 place-hover">
                                                    <div class="place-inner">
                                                        <div class="place-thumb hover-img images">
                                                            <a class="entry-thumb" data-bs-toggle="modal" data-bs-target="#view-image-{{ $image->id }}"><img src="{{ $service->getImage($image->image) }}" alt="{{ $service->service_title }}" onerror="this.onerror=null; this.src='{{ $service->vendor->getCompanyLogo($service->vendor->company_logo) }}'"></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="gallery">No Images added for this service</p>
                                    @endif
                                </div>
                            </div>
                        </div><!-- .place__left -->
                        <div class="place__left">
                           <div class="place__box place__box--npd">
                              <livewire:reviews-view :service="$service" />
                           </div>
                       </div><!-- .place__left -->
                    </div>
                </div>
            </div>

        </div>
    </div>
</main><!-- .site-main -->
   <div class="similar-places">
      <div class="container">
         <h2 class="similar-places__title title">More By {{ $service->vendor->company_name }}</h2>
         <div class="similar-places__content">
            @if($services->count())
               <div class="row">
                  @foreach ($services as $service)
                     <div class="col-lg-3 col-md-6">
                        <livewire:client-service-view :service="$service" />
                     </div>
                  @endforeach
               </div>
            @else
               <p>More Services coming soon...</p>
            @endif
         </div>
      </div>
   </div>
   <!-- .similar-places -->
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
        console.log('Add package clicked');
        e.preventDefault()
        let pricing_package_html = '<div class="row pricing-package-item mb-1">'
            pricing_package_html += '<div class="col-11"><textarea name="service_packages[]" class="form-control" style="height: 50px" id="service_package"></textarea></div>'
            pricing_package_html += '<div class="col-1" id="pricing-package-delete" style="cursor: pointer"><i class="fas fa-trash"></i></div>'
            pricing_package_html += '</div>'

        $(pricing_package_html).appendTo(pricing_package_items)
    })

    $(document.body).on("click", "#pricing-package-delete", function(event){
        console.log('Button clicked');
        let $btn = $(this);
        let $item = $btn.parent();
        if (pricing_package_items.children().length > 1) $item.remove();
    });
   </script>
   <script src={{ config('services.maps.key') }} async defer></script>
@endpush
@endsection
