@extends('layouts.master')
@section('title', 'Events')
@section('css')
<style>
   @media only screen and (max-width: 768px) {
      .member-filter .mf-right {
         margin-top: 10px;
      }
   }
</style>
@endsection
@section('content')
    <div class="container">
        <div class="member-place-wrap">
            <div class="member-wrap-top">
                <h2>My Events</h2>
            </div>
            <livewire:events-index />
        </div>
    </div>
@endsection
