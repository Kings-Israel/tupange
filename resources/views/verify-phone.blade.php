@extends('layouts.master')
@section('title', 'Verify Phone')
@section('content')
<div class="container">
   <br>
   
   <div class="row">
   <div class="col-lg-2"></div>
   <div class="col-lg-8">
      <div class="billing-form">
         <h2>Phone Number Verification</h2>
         <form class="form" action="{{ route('submit.verify.phone') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="field-group">
               <div class="row">
                  <div class="col-md-12">
                        <div class="field-input">
                           <label for="code">Enter the verification code sent to your phone.<span class="required">*</span></label>
                           <input type="number" placeholder="Enter Verification code" name="code" value="{{ old('code') }}" required>
                           <x-input-error for="code"></x-input-error>
                        </div>
                  </div>
               </div>
            </div>
            <div class="field-submit">
               <input type="submit" value="Submit" id="add-event" class="btn">
            </div>
         </form><!-- .billingForm -->
         <br>
         <span>
            Not Yet Received?
            <a href="{{ url('/resend/code') }}">Resend Code</a>
         </span>
      </div><!-- .checkout-form -->
   </div>
   <div class="col-lg-2"></div>
</div>
</div>
<br>

@endsection
