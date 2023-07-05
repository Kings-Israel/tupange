<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- favicons -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <title>@yield('title') - Tupange</title>

    <!-- Style CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fonts/jost/stylesheet.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/fontawesome-pro/css/fontawesome.css') }}" />
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/line-awesome/css/line-awesome.min.css') }}" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/slick/slick-theme.css') }}" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/slick/slick.css') }}" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/quilljs/css/quill.bubble.css') }}" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.bubble.css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/quilljs/css/quill.core.css') }}" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.core.css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/quilljs/css/quill.snow.css') }}" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/chosen/chosen.min.css') }}" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/datetimepicker/jquery.datetimepicker.min.css') }}" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css">
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/venobox/venobox.css') }}" /> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/venobox/1.9.3/venobox.css">

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script defer src="https://unpkg.com/alpinejs@3.10.3/dist/cdn.min.js"></script>
    @livewireStyles
    @yield('css')
</head>

<body>
   <div id="app">
      <div id="wrapper">
         @include('layouts.navigation')
         <main id="main" class="site-main overflow">
            @yield('content')
            @include('partials.privacy-policy')
            @include('partials.terms-of-use')
            @include('partials.company-dispute')
            @auth
               @if (auth()->user()->orders()->count() > 5)
                  @include('partials.customer-review')
               @endif
            @endauth
         </main>
         @include('layouts.footer')
         @include('layouts.flash-message')
      </div><!-- #wrapper -->
   </div>
<!-- jQuery -->
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
{{-- <script src="{{ asset('assets/js/jquery-1.12.4.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
{{-- <script src="{{ asset('assets/libs/popper/popper.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.5/umd/popper.min.js"></script>
{{-- <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.min.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.min.js"></script>
{{-- <script src="{{ asset('assets/libs/slick/slick.min.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js" integrity="sha512-HGOnQO9+SP1V92SrtZfjqxxtLmVzqZpjFFekvzZVWoiASSQgSr4cw9Kqd2+l8Llp4Gm0G8GIFJ4ddwZilcdb8A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- <script src="{{ asset('assets/libs/slick/jquery.zoom.min.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/zoom.js/0.0.1/zoom.min.js"></script>
{{-- <script src="{{ asset('assets/libs/isotope/isotope.pkgd.min.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>
{{-- <script src="{{ asset('assets/libs/quilljs/js/quill.core.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.core.js"></script>
{{-- <script src="{{ asset('assets/libs/quilljs/js/quill.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
{{-- <script src="{{ asset('assets/libs/chosen/chosen.jquery.min.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
{{-- <script src="{{ asset('assets/libs/datetimepicker/jquery.datetimepicker.full.min.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>
{{-- <script src="{{ asset('assets/libs/venobox/venobox.min.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/venobox/1.9.3/venobox.min.js"></script>
{{-- <script src="{{ asset('assets/libs/waypoints/jquery.waypoints.min.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>

<script src="https://www.google.com/recaptcha/api.js?render=6Lc0w-EkAAAAAMz_8RedmQod_vE5O7gpmGmh0z0-"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@stack('scripts')
<script>
   var today = new Date();
   var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0 so need to add 1 to make it 1!
    var yyyy = today.getFullYear();
    if(dd<10){
    dd='0'+dd
    }
    if(mm<10){
    mm='0'+mm
   }

   today = yyyy+'-'+mm+'-'+dd;
</script>
<!-- other script -->
   <script src="{{ asset('assets/js/main.js') }}"></script>
   @livewireScripts
</body>
</html>
