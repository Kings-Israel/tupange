@extends('layouts.master')
@section('title', 'Set Password')
@section('content')
<div class="container">
   <br>
   <div class="row">
   <div class="col-lg-2"></div>
   <div class="col-lg-8">
      <div class="billing-form">
         <h2>Phone Number</h2>
         <form class="form" action="{{ route('submit.phone') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="field-group">
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
