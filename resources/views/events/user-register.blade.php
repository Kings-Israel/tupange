@extends('layouts.master')
@section('title', 'Register')
@section('content')
<div class="container">
   <br>
    <div class="row">
    <div class="col-lg-2"></div>
    <div class="col-lg-8">
        <div class="billing-form">
            <h2>User Register</h2>
            <form class="form" action="{{ route('register') }}" method="POST" id="event_add" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event_id }}">
                <input type="hidden" name="role_id" value="{{ $role_id }}">
                <div class="field-group">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="field-input">
                                <label for="name">First Name<span class="required">*</span></label>
                                <input type="text" placeholder="Enter your first name" name="f_name" id="event_name" value="{{ old('f_name') }}" required>
                                <x-input-error for="f_name"></x-input-error>
                            </div>
                        </div>
                        <div class="col-md-6">
                           <div class="field-input">
                               <label for="name">Last Name<span class="required">*</span></label>
                               <input type="text" placeholder="Enter your last name" name="l_name" id="event_name" value="{{ old('l_name') }}" required>
                               <x-input-error for="name"></x-input-error>
                           </div>
                       </div>
                        <div class="col-md-6">
                           <div class="field-input">
                              <label for="event_name">Email<span class="required">*</span></label>
                              <input type="email" placeholder="Email" name="email" value="{{ $email }}{{ old('event_name') }}" required>
                              <x-input-error for="email"></x-input-error>
                          </div>
                        </div>
                        <div class="col-md-6">
                           <div class="field-input">
                              <label for="phone_number">Phone Number<span class="required">*</span></label>
                              <input type="phone_number" placeholder="Phone Number" name="phone_number" value="{{ old('phone_number') }}" required>
                              <x-input-error for="phone_number"></x-input-error>
                          </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                               <div class="field-input">
                                   <label for="email">Password<span class="required">*</span></label>
                                   <input type="password" placeholder="Password"  name="password" value="" required>
                                   <x-input-error for="password"></x-input-error>
                               </div>
                           </div>
                           <div class="col-md-6">
                              <div class="field-input">
                                 <label for="email">Password Confirm<span class="required">*</span></label>
                                 <input type="password" placeholder="Password Confirm"  name="password_confirmation" required>
                             </div>
                           </div>
                        </div>
                    </div>
                </div>
                <div class="field-submit">
                    <input type="submit" value="Register" id="add-event" class="btn">
                </div>
            </form><!-- .billingForm -->
        </div><!-- .checkout-form -->
    </div>
    <div class="col-lg-2"></div>
</div>
</div>
<br>

@endsection

