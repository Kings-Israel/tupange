@extends('layouts.master')
@section('title', 'FAQ\'s')
@section('content')
    <div class="page-title page-title--small align-left" style="background-image: url(assets/images/bg/bg-404.png);">
        <div class="container">
            <div class="page-title__content">
                <h1 class="page-title__name">FAQ's</h1>
                <p class="page-title__slogan">Frequently Asked Questions</p>
            </div>
        </div>
    </div><!-- .page-title -->
    <div class="site-content">
        <div class="container">
            <h2 class="title align-center">How may we be of help?</h2>
            <ul class="accordion first-open">
               @foreach ($faqs as $faq)
                  <li>
                     <h3 class="accordion-title"><a href="#">{{ $faq->question }}</a></h3>
                     <div class="accordion-content">
                        <p>{{ $faq->answer }}</p>
                     </div>
                  </li>
               @endforeach
            </ul>
        </div>
    </div><!-- .site-content -->
@endsection

