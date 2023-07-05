@extends('layouts.master')
@section('title', 'Event Users')
@section('css')
   <style>
      @media only screen and (max-width: 992px) {
         .header-section > .btn {
            margin-top: 5px;
         }
      }
   </style>
@endsection
@section('content')
   <livewire:event-users-index :event="$event" />
@endsection
