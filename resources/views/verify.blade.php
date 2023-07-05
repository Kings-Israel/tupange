@extends('layouts.master')
@section('title', 'Verify Email')
@section('content')
<div class="container">
   <br>
   <div class="row">
   <div class="col-lg-2"></div>
   <div class="col-lg-8">
      <div class="billing-form">
         <h2>Verify Your Email</h2>

         <div class="field-group">
            <div class="field-input">
               Before Proceeding, Please check your email for a verification link
            </div>
            <div class="field-input">
               If You did not receive an email:
            </div>
         </div>
         <form class="form" action="{{ route('verification.resend') }}" method="POST" id="event_add" enctype="multipart/form-data">
            @csrf
            <div class="field-submit">
               <input type="submit" value="Click here to resend" id="add-event" class="btn">
            </div>
         </form><!-- .billingForm -->
      </div><!-- .checkout-form -->
   </div>
   <div class="col-lg-2"></div>
</div>
</div>
<br>
@endsection
