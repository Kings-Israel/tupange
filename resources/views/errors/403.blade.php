@extends('layouts.master')

@section('title', 'Server Error')
@section('content')
<main id="main" class="site-main">
   <div class="site-content owner-content">
       <div class="container">
           <div class="member-wrap">
               <div class="member-statistical">
                  <div class="error-section">
                     <i class="fas fa-robot"></i>
                     <h3>{{ __($exception->getMessage() ?: 'Forbidden') }}</h3>
                     <h1>Oops, Something went wrong....</h1>
                  </div>
               </div><!-- .member-statistical -->
           </div><!-- .member-wrap -->
       </div>
   </div><!-- .site-content -->
</main><!-- .site-main -->
@endsection
