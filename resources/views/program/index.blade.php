@extends('layouts.master')
@section('title', 'Programs')
@section('css')
<style>
   #download-form {
      display: inline;
   }
   #program-download-btn {
      height: 35px;
   }
</style>
@endsection
@section('content')
<main id="main" class="site-main">
    <div class="site-content owner-content">
        <div class="container">
            <div class="member-place-wrap">
                <div class="member-wrap-top">
                    <h2>My Programs</h2>
                </div><!-- .member-wrap-top -->
                <livewire:event-program />
            </div><!-- .member-place-wrap -->
        </div>
    </div><!-- .site-content -->
</main><!-- .site-main -->
@endsection
