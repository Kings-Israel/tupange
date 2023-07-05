@extends('layouts.master')
@section('title', 'Event Orders')
@section('css')
<style>
   @media only screen and (max-width: 768px) {
      .member-wrap-top .btn:nth-child(1) {
         margin-top: -180px !important;
      }
      .member-wrap-top .btn:nth-child(2) {
         margin-top: -180px !important;
      }
   }
   @media only screen and (max-width: 575px) {
      .member-wrap-top .btn:nth-child(1) {
         margin-top: -180px !important;
      }
      .member-wrap-top .btn:nth-child(2) {
         margin-top: -90px !important;
      }
   }
</style>
@endsection
@section('content')
    <livewire:event-orders-index :event="$event" />
@endsection
