@extends('layouts.master')
@section('title', 'Reviews')
@section('css')
<style>
   .link-to-dashboard {
      float: right;
   }
   @media only screen and (max-width: 768px) {
      .site-content {
         margin-top: -30px;
      }
   }
   @media only screen and (max-width: 575px) {
      .site-content {
         margin-top: -40px;
      }
   }
</style>
@endsection
@section('content')
<livewire:vendor-reviews-view :vendor="$vendor" />
@endsection
