<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title>TICKET</title>
   <style>
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

        @page {
                margin: 100px 25px;
            }

        .page-break {
            page-break-after: always;
        }
   </style>
</head>
<body>
   <div class="modal-body">
      <h1 class="modal-header-title">EVENT INVITE</h1>
      <div class="row">
         <div class="col-7">
            To: {{ Str::upper($guest->first_name.' '.$guest->last_name) }}
            <br>
            {{ $guest->barcode }}
            <hr>
            DETAILS
            <br>
            <h4>{{ $guest->event->event_name }}</h4>
            <br>
            Event Starts: {{ $guest->event->event_start_date }}
            <br>
            Event Ends: {{ $guest->event->event_end_date }}
            <br>
            Location: {{ $guest->event->event_location }}
            <br>
            @if ($guest->event->custom_message != null)
               Other Details: {{ $guest->event->custom_message }}
            @endif
         </div>
         <br>
         <div class="col-5">
            {!! DNS2D::getBarcodeHTML((config('services.app_url.url')).'/event/guest/invite/'.(string) $guest->barcode, 'QRCODE') !!}
         </div>
      </div>
      <br>
   </div>
   <div class="modal-footer" style="background: #000">
      <div style="text-align: center">
         <h4>Powered By</h4><img src="{{ public_path('assets/images/assets/logo-1.png') }}" style="width: 80px">
      </div>
   </div>
</body>
</html>
