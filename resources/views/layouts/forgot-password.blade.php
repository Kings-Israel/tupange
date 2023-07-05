@extends('layouts.master')
@section('title', 'Reset Password')
@section('css')
<style>
   .site-content {
      background-color: #F8F8F8;
   }
   .filter-group {
      background-color: #fff;
      width: 70%;
      margin-left: 15%;
      padding: 15px;
      border-radius: 10px;
      border: 1px solid #eeeeee;
   }
   .success-message {
      background-color: #529525;
      color: #fff;
      font-size: 18px;
      text-align: center;
      padding: 10px 10px;
      border: 1px solid #0b2007;
      border-radius: 5px;
   }
   @media only screen and (max-width: 768px) {
      .filter-group {
         width: 100%;
         margin-left: 0;
         margin-top: 20px;
      }
   }
</style>
@endsection
@section('content')
<div class="site-content owner-content">
   <div class="container">
      <div class="member-place-wrap">
         <div class="member-wrap-top">
            <h2>Reset Password</h2>
         </div><!-- .member-place-wrap -->
         <div class="filter-group">
            <form class="d-inline p-5" method="POST" action="{{ route('password.email.confirm') }}">
               @csrf
               <div>
                  <label for="email">Email</label>
                  <input type="phone_number" class="form-control" name="email" id="email" placeholder="Enter Email Here">
               </div>
               <x-input-error for="email" />
               <br>
               <div class="row">
                  <div class="col-6">
                     @if (Session::has('success'))
                        <p class="success-message">{{ session('success') }}</p>
                     @endif
                  </div>
                  <div class="col-6">
                     <button type="submit" class="btn btn-theme" style="float: right;">{{ __('Submit') }}</button>.
                  </div>
               </div>
           </form>
         </div>
      </div>
   </div>
</div>
@endsection
