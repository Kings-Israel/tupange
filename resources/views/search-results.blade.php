@extends('layouts.master')
@section('title', 'Search Results')
@section('content')
<main id="main" class="site-main">
   <div class="filter-group filter-business">
      <div class="container">
         <div class="filter layout-02">
            <div class="member-wrap-top">
               <h2>Search Results</h2>
            </div><!-- .member-wrap-top -->
            {{-- <div class="show-map">
               <span>Map</span>
               <a href="#" class="icon-toggle"></a>
            </div> --}}
         </div>
      </div>
   </div><!-- .filter-group -->
   <div class="archive-city layout-02">
      <div class="col-left">
         <div class="main-primary">
            <div class="top-area top-area-filter">
               <div class="filter-mobile">
                  <div class="filter-mobile">
							<div class="mb-maps"><a class="mb-maps" href="#"><i class="las la-map-marked-alt"></i></a></div>
						</div>
               </div>
               <div class="filter-left">
                  <span class="result-count"><span class="count">{{ $results->count() }}</span> results</span>
               </div>
            </div>
            <div class="row" id="search-results">
               @foreach ($results as $result)
                  @if($result->type == 'services')
                     <div class="col-lg-4 col-md-6">
                        <livewire:client-service-view :service="$result->searchable" />
                     </div>
                  @elseif($result->type == 'vendors')
                     <div class="col-lg-4 col-md-6">
                        <livewire:client-vendor-view :vendor="$result->searchable" />
                     </div>
                  @endif
               @endforeach
            </div>
         </div>
      </div>
      <div class="col-right">
         <div class="filter-head">
            <h2>Maps</h2>
            <a href="#" class="close-maps">Close</a>
         </div>
         <div class="entry-map">
            <div id="place-map-filter"></div>
         </div>
      </div><!-- .col-right -->
   </div>
</main>
@push('scripts')
   <script>
   let locations = {!! json_encode($map_locations) !!}
   locations = Object.entries(locations)

   $('.show-map').on('click', function() {
      let row = $(this).closest('.row')
      $('#search-results').children('.col-lg-4').removeClass('col-lg-4').addClass('col-lg-3')
   })

   var markers = [];
      function initMap() {
         var map = new google.maps.Map(document.getElementById('place-map-filter'), {
         mapTypeControl: false,
            zoom: 7,
            center: {lat: Object.entries(locations[0][1])[0][1], lng: Object.entries(locations[0][1])[1][1]},
            styles: [
            {
               "featureType": "landscape",
               "elementType": "labels",
               "stylers": [
                  {
                        "visibility": "off"
                  }
               ]
            },
            {
               "featureType": "transit",
               "elementType": "labels",
               "stylers": [
                  {
                        "visibility": "off"
                  }
               ]
            },
            {
               "featureType": "poi",
               "elementType": "labels",
               "stylers": [
                  {
                        "visibility": "off"
                  }
               ]
            },
            {
               "featureType": "water",
               "elementType": "labels",
               "stylers": [
                  {
                        "visibility": "on"
                  }
               ]
            },
            {
               "featureType": "road",
               "elementType": "labels.icon",
               "stylers": [
                  {
                        "visibility": "off"
                  }
               ]
            },
            {
               "stylers": [
                  {
                        "hue": "#00aaff"
                  },
                  {
                        "saturation": -100
                  },
                  {
                        "gamma": 2.15
                  },
                  {
                        "lightness": 12
                  }
               ]
            },
            {
               "featureType": "road",
               "elementType": "labels.text.fill",
               "stylers": [
                  {
                        "visibility": "on"
                  },
                  {
                        "lightness": 24
                  }
               ]
            },
            {
               "featureType": "road",
               "elementType": "geometry",
               "stylers": [
                  {
                        "lightness": 57
                  }
               ]
            }
            ],
         });

         setMarkers(map);
      }

      var beaches = []
      var beaches_hover = []
      locations.forEach(location => {
         var place = location[0];

         beaches.push(
            [location[0], place, Object.entries(location[1])[0][1], Object.entries(location[1])[1][1], 1]
         )

         beaches_hover.push(
            [location[0], place, Object.entries(location[1])[0][1], Object.entries(location[1])[1][1], 1],
         );
      })

      function setMarkers(map) {
         var mapker_icon = [];
         var mapker_icon_hover = [];
         locations.forEach(location => {
            var mapker = {
               url: 'assets/images/map-pin.png',
               // This marker is 36 pixels wide by 36 pixels high.
               scaledSize: new google.maps.Size(24, 24),
               // The origin for this image is (0, 0).
               origin: new google.maps.Point(0, 0),
               // The anchor for this image is the base of the flagpole at (0, 36).
               anchor: new google.maps.Point(0, 36)
            }

            var mapker_hover = {
               url: 'assets/images/map-pin.png',
               // This marker is 36 pixels wide by 36 pixels high.
               scaledSize: new google.maps.Size(34, 34),
               // The origin for this image is (0, 0).
               origin: new google.maps.Point(0, 0),
               // The anchor for this image is the base of the flagpole at (0, 46).
               anchor: new google.maps.Point(0, 46)
            };

            mapker_icon.push(mapker);
            mapker_icon_hover.push(mapker_hover);
         })

         for (var i = 0; i < beaches.length; i++) {
         beaches[ i ].push( mapker_icon[i] );
         }

         for (var i = 0; i < beaches_hover.length; i++) {
         beaches_hover[ i ].push( mapker_icon_hover[i] );
         }

         var shape = {
            coords: [1, 1, 1, 20, 18, 20, 18, 1],
            type: 'poly'
         };

         var elements = document.querySelectorAll(".place-hover");

         var mk = '';

         for (var i = 0; i < beaches.length; i++) {
            var beach = beaches[i];
            var contentString = '<div class="places-item" data-title="'+beach[1]+'" data-lat="'+beach[2]+'" data-lng="'+beach[3]+'" data-index="1">'+
               '<div class="places-item__info">'+
               '<h3>'+beach[1]+'</h3>' +
               '</div>'+
               '</div>';

            var infowindow = new google.maps.InfoWindow({
               content: contentString
            });
            var marker = new google.maps.Marker({
               position: {lat: beach[2], lng: beach[3]},
               map: map,
               icon: beach[5],
               shape: shape,
               title: beach[1],
               zIndex: beach[4]
            });
            marker.data = beaches[i];
            marker.addListener('click', function() {
               infowindow.setContent("<div id='infowindow'>"+ this.data[1] +"</div>");
               infowindow.open(map, this);
            });

            markers[marker.title] = marker;

            mk = marker.title;
         }

         elements.forEach(function(element) {
               element.addEventListener("mouseenter", function() {
               for (var i = 0; i < beaches_hover.length; i++) {
                  var bh = beaches_hover[i];
                  if (element.dataset.maps_name == bh[0]) {
                        markers[element.dataset.maps_name].setIcon(bh[5]);
                     }
                  }

               });
               element.addEventListener("mouseleave", function() {
               for (var i = 0; i < beaches.length; i++) {
                  var b = beaches[i];
                  if (element.dataset.maps_name == b[0]) {
                        markers[element.dataset.maps_name].setIcon(b[5]);
                     }
                  }
               // closeLastOpenedInfoWindow();
               });
         });
      }
   </script>
   <script src="{{ config('services.maps.key') }}" async defer></script>
@endpush
@endsection
