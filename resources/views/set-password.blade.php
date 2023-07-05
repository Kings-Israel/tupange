@extends('layouts.master')
@section('title', 'Set Password')
@section('content')
<div class="container">
   <br>
   <div class="row">
   <div class="col-lg-2"></div>
   <div class="col-lg-8">
      <div class="billing-form">
         <h2>Set Password and Phone Number</h2>
         <p style="color: red">Please set a new password and enter your phone number. Your email is the current set password.</p>
         <form class="form" action="{{ route('submit-credentials') }}" method="POST" id="event_add" enctype="multipart/form-data">
            @csrf
            <div class="field-group">
               <div class="row">
                  <div class="col-md-6">
                        <div class="field-input">
                           <label for="name">Password<span class="required">*</span></label>
                           <input type="password" placeholder="Enter password" name="password" id="password" required>
                           <x-input-error for="password"></x-input-error>
                        </div>
                  </div>
                  <div class="col-md-6">
                     <div class="field-input">
                        <label for="name">Confirm Password<span class="required">*</span></label>
                        <input type="password" placeholder="Re-enter password" name="password_confirmation" id="password_confirm" required>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-12">
                        <div class="field-input">
                           <label for="name">Enter Your Phone Number<span class="required">*</span></label>
                           <input type="number" placeholder="Enter phone number" name="phone_number" required>
                           <x-input-error for="phone_number"></x-input-error>
                        </div>
                  </div>
               </div>
            </div>
            <div class="field-submit">
               <input type="submit" value="Submit" id="add-event" class="btn">
            </div>
         </form><!-- .billingForm -->
      </div><!-- .checkout-form -->
   </div>
   <div class="col-lg-2"></div>
</div>
</div>
<br>

@endsection

