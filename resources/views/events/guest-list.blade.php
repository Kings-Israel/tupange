@extends('layouts.master')
@section('title', 'Guest List')
@section('css')
   <style>
      .fas {
         font-size: 26px;
         margin: 10px 2px 0px 2px;
         transition: .2s ease;
      }
      .fas:hover {
         cursor: pointer;
         font-size: 28px;
         color: rgb(153, 198, 206);
      }
      .field-check label {
         margin-top: 5px;
         max-width: 100% !important;
      }
      .badge {
         height: 40px;
         background-color: #F58C1C;
         float: right;
         margin-top: 8px;
      }
      .badge:hover {
         cursor: pointer;
         background-color: #eeac67;
      }
      .badge i {
         margin-top: 2px;
         font-size: 18px;
      }
      .actions-bubble {
         display: flex;
      }
      .search-results {
         max-width: 150px !important;
      }
      .guests-action-btns {
         display: flex;
      }
      @media only screen and (max-width: 768px) {
         .actions-bubble {
            display: flex;
            justify-content: end;
         }
         .search-results {
            position: relative;
            right: 30px;
            bottom: 40px;
            margin-left: 25px;
         }
      }
      @media only screen and (max-width: 576px) {
         .guests-action-btns {
            display: block;
         }
      }
   </style>
@endsection
@section('content')
   <livewire:event-guest-list :event="$event" />
@endsection
@push('scripts')
   <script>
      var guests = []
      function selectedGuest(e) {
         if (guests.includes(e.value)) {
            let index = guests.findIndex(guest => guest == e.value)
            guests.splice(index, 1)
         } else {
            guests.push(e.value)
         }
         if (guests.length > 0) {
            $('#send-selected-invites').removeAttr('hidden')
            $('#send-all-invites').attr('hidden', 'hidden')
         } else {
            $('#send-selected-invites').attr('hidden', 'hidden')
            $('#send-all-invites').removeAttr('hidden')
         }
      }

      $('#send-selected-invites').on('click', function(e) {
         e.preventDefault();
         $('#send-invites-form').submit()
      })
   </script>
@endpush
