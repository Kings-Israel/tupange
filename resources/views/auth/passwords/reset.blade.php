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
            <form method="POST" action="{{ url('/reset-password') }}">
               @csrf
               <input type="hidden" name="token" value="{{ $token }}">
               <label for="email">{{ __('E-Mail Address') }}</label>
               <input id="email" type="phone_number" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" readonly>
               @error('email')
                  <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                  </span>
               @enderror
               <br>
               <label for="password">{{ __('Password') }}</label>
               <div class="d-flex">
                  <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" autofocus>
                  <i class="las la-eye" style="font-size: 24px; margin-left: 10px; cursor:pointer" id="password-view"></i>
                  <i class="las la-eye-slash" style="font-size: 24px; margin-left: 10px; cursor:pointer" id="password-hide" hidden></i>
               </div>
               @error('password')
                  <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                  </span>
               @enderror
               <br>
               <label for="password-confirm">{{ __('Confirm Password') }}</label>
               <div class="d-flex">
                  <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                  <i class="las la-eye" style="font-size: 24px; margin-left: 10px; cursor:pointer" id="password-confirmation-view"></i>
                  <i class="las la-eye-slash" style="font-size: 24px; margin-left: 10px; cursor:pointer" id="password-confirmation-hide" hidden></i>
               </div>

               <br>
               <button type="submit" class="btn" style="float: right">
                  {{ __('Reset Password') }}
               </button>
            </form>
         </div>
      </div>
   </div>
</div>
@endsection
@push('scripts')
   <script>
      // let email = $('#email').val()
      // let first_three = email.slice(0, 3)
      // let last_three = email.slice(-3, email.length)
      // console.log(last_three)
      // $('#email').val(first_three+'********'+last_three)

      $('#password-view').on('click', function() {
         $(this).attr('hidden', 'hidden')
         $('#password-hide').removeAttr('hidden')
         $('#password').attr('type', 'text')
      })
      $('#password-hide').on('click', function() {
         $(this).attr('hidden', 'hidden')
         $('#password-view').removeAttr('hidden')
         $('#password').attr('type', 'password')
      })
      $('#password-confirmation-view').on('click', function() {
         $(this).attr('hidden', 'hidden')
         $('#password-confirmation-hide').removeAttr('hidden')
         $('#password-confirm').attr('type', 'text')
      })
      $('#password-confirmation-hide').on('click', function() {
         $(this).attr('hidden', 'hidden')
         $('#password-confirmation-view').removeAttr('hidden')
         $('#password-confirm').attr('type', 'password')
      })
   </script>
@endpush
