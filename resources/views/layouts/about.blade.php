@extends('layouts.master')
@section('title', 'About')
@section('content')
   <div class="page-title page-title--small align-left" style="background-image: url(assets/images/bg/bg-about.png);">
      <div class="container">
         <div class="page-title__content">
               <h1 class="page-title__name">About Us</h1>
               <p class="page-title__slogan">Know more about us</p>
         </div>
      </div>
   </div><!-- .page-title -->
   <div class="site-content">
      <div class="container">
         <div class="company-info flex-inline">
            <img src="{{ $about->about_us_image }}" alt="Our Company">
            <div class="ci-content">
               <h2>About Tupange</h2>
               <p>{{ $about->content }}</p>
            </div>
         </div><!-- .company-info -->
         <br>
         <div class="owner-box">
            <div class="row">
               <div class="col-lg-4">
                  <div class="ob-item">
                     <div class="ob-head">
                        <h1>{{ $about->step_one_title }}</h1>
                     </div>
                     <div class="ob-content">
                        <span class="job">{{ $about->step_one_content }}</span>
                     </div>
                  </div>
               </div>
               <div class="col-lg-4">
                  <div class="ob-item">
                     <div class="ob-head">
                        <h1>{{ $about->step_two_title }}</h1>
                     </div>
                     <div class="ob-content">
                        <span class="job">{{ $about->step_two_content }}</span>
                     </div>
                  </div>
               </div>
               <div class="col-lg-4">
                  <div class="ob-item">
                     <div class="ob-head">
                        <h1>{{ $about->step_three_title }}</h1>
                     </div>
                     <div class="ob-content">
                        <span class="job">{{ $about->step_three_content }}</span>
                     </div>
                  </div>
               </div>
            </div>
         </div><!-- .owner-box -->
         <br>
         <div class="company-info flex-inline">
            <img src="{{ $about->for_planner_image }}" alt="Our Company">
            <div class="ci-content">
               <h2>For Planners</h2>
               <p>{{ $about->for_planner_content }}</p>

               <a href="{{ route('register') }}" class="btn btn-info open-signup" style="background-color: #1775B1" title="Register">Get Started</a>
            </div>
         </div><!-- .company-info -->
         <br>
         <div class="company-info flex-inline">
            <div class="ci-content">
               <h2>For Vendors</h2>
               <p>{{ $about->for_vendor_content }}</p>

               <a href="{{ route('login') }}" class="btn open-login" title="Login">Login to register as a vendor</a>
            </div>
            <img src="{{ $about->for_vendor_image }}" alt="Our Company">
         </div><!-- .company-info -->
         <br>
      </div>
   </div><!-- .site-content -->
@endsection
