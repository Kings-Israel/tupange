<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title>{{ $guest->event->event_name }} Ticket</title>
   <style>
      *,
      *::before,
      *::after {
         margin: 0;
         padding: 0;
         box-sizing: 'border-box';
      }
      body {
         background: #ffffff;
         color:#4e5c79;
         font-size:16px;
         font-family: 'Muli', sans-serif;
         margin: 0;
         overflow-x: hidden !important;
         font-weight: 400;
      }
      html {
         position: relative;
         min-height: 100%;
         background: #ffffff;
      }
      h1,
      h2,
      h3,
      h4,
      h5,
      h6 {
         color:#2D3954;
         font-weight:700;
         text-transform: capitalize;
         font-family: 'Jost', sans-serif;
      }

      h5,
      h6 {
         color:#2D3954;
      }
      h1 {
      line-height: 40px;
      font-size: 36px; }

      h2 {
      line-height: 36px;
      font-size: 30px; }

      h3 {
      line-height: 30px;
      font-size: 24px; }

      h4 {
      line-height: 26px;
      font-size: 21px; }

      h5 {
      line-height: 22px;
      font-size: 18px;
      }

      h6 {
      line-height: 20px;
      font-size: 16px;
      }

      .ticket-header {
      margin: 50px;
      }

      .ticket-header-image {
      margin-top: -100px;
      float: right;
      }

      .ticket-header-image img {
      width: 220px;
      height: 200px;
      object-fit: contain;
      }

      .event_details {
      margin: 50px;
      }

      .event_details .side-2 {
      margin-top: -150px;
      margin-right: 20px;
      float: right;
      }

      .guest-name-section .guest-name {
      font-family: 'Jost', sans-serif;
      font-weight: 700;
      font-size: 18px;
      }
      .guest-barcode {
      margin-bottom: 10px;
      }
      .event-details-header-title {
         margin-top: 10px;
         font-family: 'Jost', sans-serif;
         font-weight: 700;
         font-size: 14px;
      }
      .event-location-link {
         width: 21%;
         background-color: #648E1B;
         padding: 5px;
         padding-left: 8px;
         border-radius: 5px;
      }
      .event-location-link:hover {
         background-color: #a1c760
      }
      .event-location-link a {
         text-decoration: none;
         color: #ffffff;
         justify-content: center;
      }
      .event-location-section {
         max-width: 50%;
      }
      .event-start-time, .event-end-time, .event-location {
         font-family: 'Jost', sans-serif;
         font-weight: 700;
         font-size: 18px;
      }
      .barcode {
         margin-top: -80px;
      }
      .barcode-map {
         margin-top: 10px;
      }
      .modal-footer {
         position: absolute;
         bottom: 0;
         width: 100%;
      }
      @page {
         margin: 100px 25px;
      }

      .page-break {
         page-break-after: always;
      }
   </style>
</head>
<body>
   <div class="ticket">
      <div class="ticket-header">
         <h2 class="ticket-header-title">{{ $guest->event->event_name }} Ticket</h2>
         @if ($guest->ticket_title)
            <h5 class="ticket-title">{{ Str::upper($guest->ticket_title) }}</h5>
         @endif
         <div class="ticket-header-image">
            <img src="{{ $guest->event->getEventCoverImage($guest->event->event_poster) }}" alt="">
         </div>
      </div>
      <div class="event_details">
         <div class="side-1">
            <div class="guest-name-section">
               To:
               <span class="guest-name">{{ Str::upper($guest->names != '' ? $guest->names : $guest->first_name.' '.$guest->last_name) }}</span>
            </div>
            <br>
            <div class="guest-barcode">
               {{ $guest->barcode }} @if ($guest->guests != '') (admit {{ $guest->guests }}) @endif
            </div>
            <hr>
            <div class="event-details">
               <div class="event-details-header">
                  <span class="event-details-header-title">
                     Event Details
                  </span>
               </div>
               <br>
               <div class="event-name">
                  <h4>{{ $guest->event->event_name }}</h4>
               </div>
               <br>
               <div class="event-times">
                  <div class="event-start-time-details">
                     Event Starts:
                     <span class="event-start-time">{{ Carbon\Carbon::parse($guest->event->event_start_date)->format('d M Y') }}</span>
                     at
                     <span class="event-start-time">{{ Carbon\Carbon::parse($guest->event->event_start_date)->format('H:i:s a') }}</span>
                  </div>
                  <br>
                  <div class="event-start-time-details">
                     Event Ends:
                     <span class="event-end-time">
                        {{ Carbon\Carbon::parse($guest->event->event_end_date)->format('d M Y') }}
                     </span>
                     at
                     <span class="event-end-time">
                        {{ Carbon\Carbon::parse($guest->event->event_end_date)->format('H:i:s a') }}
                     </span>
                  </div>
               </div>
               <br>
               <div class="event-location-section">
                  Location:
                  <span class="event-location">{{ $guest->event->event_location }}</span>
               </div>
               <br>
               @if ($guest->event->custom_message != null)
                  <div class="event-meta-details-section">
                     Other Details:
                     <span class="event-meta-details">{{ $guest->event->custom_message }}</span>
                  </div>
               @endif
               @if($map)
                  <div class="barcode-map">
                     <span class="barcode-map-title">Scan Here for Map</span>
                     {!! DNS2D::getBarcodeHTML("https://www.google.com/maps/search/?api=1&query=".$guest->event->event_location_lat.",".$guest->event->event_location_long."", 'QRCODE', 2.6, 2.6) !!}
                  </div>
               @endif
            </div>
         </div>
         <br>
         <div class="side-2">
            <div class="barcode">
               <span class="barcode-title">Scan Here for Event Details</span>
               {!! DNS2D::getBarcodeHTML(env('APP_URL').'/event/ticket/'.(string) $guest->barcode, 'QRCODE', 5.6, 5.6) !!}
            </div>
         </div>
      </div>
      <br>
   </div>
   <div class="modal-footer" style="background: #000">
      <div style="text-align: center">
         <h4>Powered By</h4><img src="{{ public_path('assets/images/assets/logo-1.png') }}" style="width: 120px">
      </div>
   </div>
</body>
</html>
