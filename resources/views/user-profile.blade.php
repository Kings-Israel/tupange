@extends('layouts.master')
@section('title', 'Profile')
@section('content')
<main id="main" class="site-main">
   <div class="container">
      <div class="col-md-7">
      </div>
      <div class="">
         <div class="listing-content">
               <h2>Edit User Profile</h2>
               <form action="{{ route('user.profile.update') }}" method="POST" class="" id="vendor-edit-form" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="user_id" value="{{ $user->id }}">
                  <div class="listing-box" id="genaral">
                     <div class="row">
                        <div class="col-lg-6">
                           <div class="field-group field-input">
                              <label>First Name</label>
                              <input type="text"  name="first_name" value="{{ $user->f_name }}">
                              <x-input-error for="first_name"></x-input-error>
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="field-group field-input">
                                 <label>Last Name</label>
                                 <input type="text"  name="last_name" value="{{ $user->l_name }}">
                                 <x-input-error for="last_name"></x-input-error>
                           </div>
                        </div>
                     </div>
                     <br>
                     <div class="row">
                        <div class="col-lg-6">
                           <div class="field-group field-input">
                              <label>Phone Number</label>
                              <input type="text" id="user_phone_number" placeholder="Phone Number" name="phone_number" value="{{ $user->phone_number }}">
                              <x-input-error for="phone_number"></x-input-error>
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="field-group field-input">
                              <label>Email</label>
                              <input type="email" placeholder="Email" name="email" value="{{ $user->email }}">
                              <x-input-error for="email"></x-input-error>
                           </div>
                        </div>
                     </div>
                     <br>
                     <div class="row">
                        <div class="col-lg-4">
                           <div class="field-group field-input">
                              <label for=" Old Password">Enter Your Current Password</label>
                              <input type="password" placeholder="Current Password" name="current_password">
                              <x-input-error for="current_password"></x-input-error>
                           </div>
                        </div>
                        <div class="col-lg-4">
                           <label for="New Password">Enter New Password</label>
                           <input type="password" placeholder="New Password" name="password">
                           <x-input-error for="password"></x-input-error>
                        </div>
                        <div class="col-lg-4">
                           <label for="New Password">Confirm New Password</label>
                           <input type="password" placeholder="Confirm New Password" name="password_confirmation">
                        </div>
                     </div>
                     <br>
                     <div class="field-inline">
                        <div class="field-group field-file">
                           <label>Your Avatar</label>
                           <label class="preview">
                              <input type="file" name="user_avatar" accept=".jpg,.png,.jpeg" class="upload-file" data-max-size="50000" value="{{ $user->getAvatar($user->avatar)  }}">
                              <img class="img_preview" src="{{ $user->getAvatar($user->avatar) }}" alt="" />
                              <i class="la la-cloud-upload-alt"></i>
                           </label>
                           <div class="field-note">Maximum file size: 5 MB.</div>
                           <x-input-error for="user_avatar"></x-input-error>
                        </div>
                     </div>
                  </div><!-- .listing-box -->
                  <div class="field-group field-submit d-flex justify-content-between">
                     <a href="{{ url('/') }}" class="btn m-3" style="background-color: red" title="Cancel">Cancel</a>
                     <input type="submit" value="Submit" id="vendor-submit" class="btn">
                  </div>
               </form>
         </div><!-- .listing-content -->
      </div>
   </div>
</main>
@endsection
