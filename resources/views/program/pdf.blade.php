<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <style>
      body {
         background: #ffffff;
         color:#27282b;
         font-size:16px;
         font-family: 'Muli', sans-serif;
         margin: 0;
         overflow-x: hidden !important;
         font-weight: 400;
      }
      html {
         min-height: 100%;
         background: #fefffd;
      }
      h1,
      h2,
      h3,
      h4,
      h5,
      h6 {
         color:#212631;
         font-weight:700;
         text-transform: capitalize;
      }

      h5,
      h6 {
         color:#2D3954;
      }

      h1 {
        line-height: 40px;
        font-size: 36px;
      }

      h2 {
        line-height: 36px;
        font-size: 30px;
      }

      h3 {
        line-height: 30px;
        font-size: 24px;
      }

      h4 {
        line-height: 26px;
        font-size: 21px;
      }

      h5 {
        line-height: 22px;
        font-size: 18px;
      }

      h6 {
        line-height: 20px;
        font-size: 16px;
      }

      table {
         width: 100%;
      }
      tbody {
         border-bottom: 1px solid #000;
      }

      thead {
         border-bottom: 1px solid #000;
      }
      .program-header {
         margin: -25px 25px 25px 60px;
      }

      .program-content {
         margin: 70px;
      }

      .program-event-poster {
         float: right;
         margin-top: -120px;
         margin-right: 10px;
      }

      .program-event-poster img {
         width: 170px;
         object-fit: contain;
         border-radius: 5px
      }

      .event-time {
         margin-top: -50px;
         width: 75%;
      }

      .activities-table {
         margin-top: 15px;
         margin-bottom: 10px;
      }

      .direction-map {
         margin-top:10px;
         border-bottom: 1px solid #000;
      }
      .map {
         padding-bottom: 5px;
         width: 100%;
         text-align: center;
      }

      .map-qr {
         display: flex;
         margin-left: 260px;
         padding: 5px;
      }

      .direction-description {
         width: 100%;
         text-align: center;
      }
      .direction-description h6 {
         border-bottom: .5px solid #000;
      }

      .direction-description-text {
         margin-top: -20px;
      }

      .contacts {
         margin-top: -20px;
      }

      .footer {
         margin-bottom: -20px;
         background-color: #000;
         position: absolute;
         bottom: 0;
         left: 0;
         right: 0;
      }

      .footer h6 {
         color: #000;
         margin-top: 5px;
         margin-right: 2px;
         float: right;
      }

      .footer img{
        float: right;
        padding: 4px 4px 2px 3px;
        background-color: #000;
      }

      @page {
         margin: 50px 1px;
      }

      .page-break {
         page-break-after: always;
      }
   </style>
   <title>{{ $program->event_name }}</title>
</head>
<body>
   <div class="program">
      <div class="program-header">
         <div class="program-title">
            <h1>
               {{ $program->event_name }}
            </h1>
            <h2>
               {{ $program->event_type }}
            </h2>
         </div>
         <div class="program-event-poster">
            @if($program->event)
               <img src="{{ public_path('storage/event/poster/'.$program->event->event_poster) }}" alt="">
            @endif
         </div>
      </div>
      <div class="program-content">
         <div class="event-time">
            <div class="event-start">
               Starts At: <strong>{{ Carbon\Carbon::parse($program->start_date)->format('H:i') }}</strong> on <strong>{{ Carbon\Carbon::parse($program->start_date)->format('M d, Y') }}</strong> upto
               <strong>{{ Carbon\Carbon::parse($program->end_date)->format('H:i') }}</strong> on <strong>{{ Carbon\Carbon::parse($program->end_date)->format('M d, Y') }}</strong>
            </div>
            {{-- <div class="event-end">
               Event End At: <strong>{{ Carbon\Carbon::parse($program->event_date)->format('H:i, M d, Y') }}</strong>
            </div> --}}
         </div>

         <table class="activities-table">
            <h5>Program:</h5>
            <thead>
               <tr>
                  <td>Activity</td>
                  <td>Description</td>
                  <td>Starts At</td>
                  <td>Ends At</td>
               </tr>
            </thead>
            <tbody class="activities">
               @foreach ($program->eventProgramActivities as $count => $activity)
                  <tr class="table-row">
                     <td>{{ $count + 1 }}.{{ $activity->activity }}</td>
                     <td>{{ $activity->actions }}</td>
                     <td>{{ $activity->start_time->format('H:i') }}</td>
                     <td>{{ $activity->end_time->format('H:i') }}</td>
                  </tr>
               @endforeach
            </tbody>
         </table>

         @if ($program->contacts)
            <h6 class="contacts-header">For More Information, Contact: </h6>
            <div class="contacts">
               <table id="contacts-table">
                  <tbody>
                     @for ($i = 0; $i < count($program->contacts); $i++)
                        <tr>
                           <td>
                              {{ key($program->contacts[$i]) }}
                           </td>
                           <td>
                              {{ $program->contacts[$i][key($program->contacts[$i])] }}
                           </td>
                        </tr>
                     @endfor
                  </tbody>
               </table>
            </div>
         @endif

         <div class="direction-map">
            <div class="direction-description">
               <h6>Direction Description</h6>
               <p class="direction-description-text">{{ $program->direction_description }}</p>
            </div>
            <div class="map">
               <span>Scan Here To View Map:</span>
               <span class="map-qr">
                  {!! DNS2D::getBarcodeHTML("https://www.google.com/maps/search/?api=1&query=".$program->venue_location_lat.",".$program->venue_location_long."", 'QRCODE', 3.8, 3.8) !!}
               </span>
               {{-- <img src="http://maps.googleapis.com/maps/api/staticmap?zoom=17&size=512x350&maptype=hybrid&markers=icon:http://maps.google.com/mapfiles/ms/icons/red.png|{{ $program->venue_location_lat }},{{ $program->venue_location_long }}&key={{ config('services.maps.partial_key') }}" alt=""> --}}
            </div>
         </div>
      </div>

      <div class="program-footer">
         <div class="footer">
            <img src="{{ public_path('assets/images/assets/logo-1.png') }}" style="width: 120px">
            <h6>Powered By</h6>
         </div>
      </div>
   </div>

</body>
</html>
