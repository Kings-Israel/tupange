<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- favicons -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <title>Tupange.com</title>

    <!-- Style CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fonts/jost/stylesheet.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/line-awesome/css/line-awesome.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/fontawesome-pro/css/fontawesome.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/slick/slick-theme.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/slick/slick.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/quilljs/css/quill.bubble.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/quilljs/css/quill.core.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/quilljs/css/quill.snow.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/chosen/chosen.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/datetimepicker/jquery.datetimepicker.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/venobox/venobox.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}" />
</head>

<body>
<div id="wrapper">
    <main id="main" class="site-main overflow">
       <div class="container">
          <h1 class="modal-header-title">EVENT INVITE</h1>
          <div class="login-form">
             <div class="submit-section">
                <div class="row">
                   <div class="form-group col-md-12">
                      <div class="row">
                         <div class="col-lg-8">
                            To: {{ Str::upper($guest->first_name) }} {{ Str::upper($guest->last_name) }}
                            <br>
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
                         {{-- <div class="col-lg-4">
                            {!! DNS2D::getBarcodeHTML((string) $guest->created_at, 'QRCODE') !!}
                         </div> --}}
                      </div>
                      <br>
                   </div>
                </div>
             </div>
          </div>
       </div>
      @include('partials.privacy-policy')
      @include('partials.terms-of-use')
      @yield('content')
    </main>
    @include('layouts.footer')
</div><!-- #wrapper -->

<script src="{{ asset('assets/js/jquery-1.12.4.js') }}"></script>
<script src="{{ asset('assets/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/libs/slick/slick.min.js') }}"></script>
<script src="{{ asset('assets/libs/slick/jquery.zoom.min.js') }}"></script>
<script src="{{ asset('assets/libs/isotope/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('assets/libs/quilljs/js/quill.core.js') }}"></script>
<script src="{{ asset('assets/libs/quilljs/js/quill.js') }}"></script>
<script src="{{ asset('assets/libs/chosen/chosen.jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script>
<script src="{{ asset('assets/libs/venobox/venobox.min.js') }}"></script>
<script src="{{ asset('assets/libs/waypoints/jquery.waypoints.min.js') }}"></script>

<!-- orther script -->
<script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
