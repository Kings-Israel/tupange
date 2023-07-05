@extends('layouts.master')
@section('title', 'Services')
@section('css')
<style>
   .no-services-found-text {
      margin-top: 20px;
      margin-bottom: 40px;
   }
</style>
@endsection
@section('content')
<main id="main" class="site-main">
   <livewire:favorited-services />
</main><!-- .site-main -->
@push('scripts')
   <script>
      if (screen.width <= 992) {
         $('.area-places').removeClass('layout-4col')
         $('.area-places').addClass('layout-3col')
      }
   </script>
@endpush
@endsection
