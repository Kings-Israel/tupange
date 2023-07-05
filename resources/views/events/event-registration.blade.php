@extends('layouts.master')
@section('title', 'Event Registration')
@section('css')
   <style>
      .search-results {
         width: 100px !important;
         margin-top: -12px !important;
         margin-left: 14px !important;
      }
      .result {
         color: #fff;
      }
      .result:hover {
         cursor: pointer;
      }
      @media only screen and (max-width: 992px) {
         .header-section > .btn {
            margin-top: 5px;
         }
      }
   </style>
@endsection
@section('content')
<main id="main" class="site-main">
   <div class="site-content owner-content">
      <div class="container">
         <div class="member-place-wrap">
            <div class="member-wrap-top mt-2">
               <h2>Event Registration</h2>
               <div class="header-section">
                  <a class="btn" href="{{ route('events.show', $event) }}">Back to My Event</a>
                  <a href="{{ route('client.event.tickets', $event) }}" class="btn btn-info" style="background: #1DA1F2">
                     Manage Tickets
                  </a>
                  <button class="btn" data-bs-toggle="modal" data-bs-target="#register-guest-{{ $event->id }}" style="background-color: #F58C1C">Register Guest</button>
                  @include('partials.register-guest')
               </div>
            </div><!-- .member-place-wrap -->
         </div><!-- .member-wrap-top -->
         <livewire:event-registration :event="$event" />
      </div>
   </div><!-- .site-content -->
</main><!-- .site-main -->
@endsection
