@extends('layouts.master')
@section('title', 'Home')
@section('css')
<style>
   .home-create-program-btn {
      /* margin-top: 10px; */
   }
   #create-event-title {
      /* max-width: 70%; */
   }
   .welcome-auth-section {
      margin-top: 10px;
      margin-bottom: 5px;
   }
   input[type="date"]::-webkit-calendar-picker-indicator {
      background: transparent;
      bottom: 0;
      color: transparent;
      cursor: pointer;
      height: auto;
      left: 0;
      position: absolute;
      right: 0;
      top: 0;
      width: auto;
   }
   .home-action-btns {
         margin-top: 15px;
      }
   @media only screen and (max-width: 998px) {
      .home-action-btns {
         display: flex;
         flex-direction: column;
         margin-top: 5px;
      }
      .home-action-btns .create-event-btn {
         width: 25%;
         margin-top: 5px;
      }
      #create-event-title {
         width: 50%;
      }
   }
   @media only screen and (max-width: 575px) {
      .banner-wrap {
         position: inherit;
      }

      .home-action-btns {
         display: flex;
         flex-direction: column;
         margin-top: 10px;
      }

      .home-action-btns .create-event-btn {
         width: 50%;
      }
   }
   @media only screen and (max-width: 415px) {
      #create-event-title {
         display: none;
      }
   }
</style>
@endsection
@section('content')
   <section class="banner-wrap">
      <div class="flex">
         <div class="banner-left"></div>
         <div class="banner slick-sliders">
               <div class="banner-sliders slick-slider" data-item="1" data-arrows="false" data-dots="true">
                  <div class="item"><img src="{{ asset('assets/images/bg/hero.jpg') }}" alt="Banner"></div>
               </div>
         </div><!-- .banner -->
      </div>
      <div class="container">
         <div class="banner-content">
            <h1 class="offset-item">Plan your Event with us.</h1>
            {{-- <h3 class="offset-item" id="home-search-header">Need Help Planning. Search for services here...</h3> --}}
            <h3 class="offset-item" id="home-search-header">Search for services here...</h3>
            <form action="{{ route('client.services.filtered') }}" method="POST" class="service-search">
               @csrf
               {{-- <div id="other-search">
                  <div class="site-banner__search layout-02 offset-item desktop-search mt-2">
                     <div class="field-input">
                        <label for="s">Event</label>
                        <div id="selected-event-type">
                           <input class="site-banner__search__input open-suggestion" type="text" id="event_type" name="event_type" placeholder="Select Event Type" autocomplete="off">
                           <div class="search-suggestions name-suggestions">
                              <ul class="event-types" style="z-index: 999">
                                 @foreach ($eventTypes as $event)
                                    <li><a href="{{ $event }}" onclick="getEventType('{{ $event }}')"><span>{{ $event }}</span></a></li>
                                 @endforeach
                              </ul>
                           </div>
                        </div>
                     </div>

                     <div class="field-input">
                        <label for="loca">When</label>
                        <input class="site-banner__search__input open-suggestion" type="text" id="event_date" placeholder="Select Event Date" autocomplete="off">
                        <div class="search-suggestions name-suggestions">
                           <ul class="date-types" style="z-index: 999">
                              <li><a class="select-date" href="#"><span>Select A Specific Date Or</span></a></li>
                              <li><a class="select-month" href="#"><span>Select Month and Year</span></a></li>
                              <li><a class="" href="#"><span>Undecided on day</span></a></li>
                           </ul>
                        </div>
                        <input hidden class="site-banner__search__input month-select date-pick" type="month" name="when_month" autocomplete="off">
                        <input hidden class="site-banner__search__input date-select date-pick" type="date" name="when_date" autocomplete="off">
                     </div>
                  </div>
                  <div class="site-banner__search layout-02 offset-item desktop-search custom-event-input mt-2" hidden  style="z-index: 1">
                     <div class="field-input">
                        <input class="site-banner__search__input custom_event_input" type="text" id="event_type_custom" name="custom_event_type" placeholder="Enter Event Type" autocomplete="off">
                     </div>
                  </div>
               </div> --}}
               <div class="site-banner__search layout-02 desktop-search mt-2" id="category-search" style="z-index: 1">
                  <div class="category-search" >
                     <div class="field-input">
                        <label for="s">Find</label>
                        <input type="hidden" name="category" class="category">
                        <input class="site-banner__search__input open-suggestion" type="text" placeholder="Select Categories" autocomplete="off">
                        <div class="search-suggestions">
                           <ul class="category-list">
                              @foreach ($categories as $category)
                                 <li><a class="category" href="#"><i class="{{ $category->icon }}"></i><span class="category-name">{{ $category->name }}</span></a></li>
                              @endforeach
                           </ul>
                        </div>
                     </div><!-- .site-banner__search__input -->
                     <div class="field-submit" style="margin-right: -15px">
                        <button style="background-color: #83B924" class="submit-search"><i class="las la-search la-24-black"></i></button>
                     </div>
                  </div>
               </div>
            </form><!-- .site-banner__search -->
            <div class="desktop-search m-2 show-selected-categories" hidden>
               <p>You have selected: (Select more above...)</p>
               <div class="selected-categories" ></div>
            </div>

            <form action="{{ route('client.services.filtered') }}" class="mobile-search" method="POST">
               @csrf
               {{-- <div class="field-input">
                  <label for="s">Event Type</label>
                  <select name="event_type" class="form-control" id="mobile-select-event-type">
                     <option value="">Select Event Type</option>
                     @foreach ($eventTypes as $event)
                        <option value="{{ $event }}">{{ $event }}</option>
                     @endforeach
                  </select>
               </div>
               <div class="field-input mt-2">
                  <input hidden class="site-banner__search__input custom_event_input_mobile" type="text" id="event_type_custom_mobile" name="custom_event_type" placeholder="Enter Event Type" autocomplete="off">
               </div> --}}
               {{-- <div class="field-input">
                  <label for="loca">When</label>
                  <select name="select-date-mobile" class="form-control" id="mobile-select-date">
                     <option value="" selected disabled>Select Event Date</option>
                     <option value="select-date">Select Specific Date Or</option>
                     <option value="select-month">Select Month And Year</option>
                  </select>
                  <input hidden class="site-banner__search__input mobile-month-select form-control" id="mobile-month-select" type="month" name="when_month" autocomplete="off">
                  <input hidden class="site-banner__search__input mobile-date-select form-control" id="mobile-date-select" type="date" name="when_date" autocomplete="off">
               </div><!-- .site-banner__search__input --> --}}
               <div class="field-input">
                  <label for="s">Find</label>
                  <select name="category" class="form-control" id="mobile-select-category">
                     <option value="">Select Category</option>
                     @foreach ($categories as $category)
                        <option value="{{ $category->name }}">{{ $category->name }}</option>
                     @endforeach
                  </select>
               </div><!-- .site-banner__search__input -->
               <div class="mt-2">
                  <button class="btn" style="background: #486b0a" id="mobile-submit-search">Search</button>
               </div>
            </form><!-- .site-banner__search -->
            <h3 class="offset-item" id="create-event-title">Create your event or program and start planning</h3>
            @guest
            <div class="welcome-auth-section">
               <span class="open-login" href="{{ route('login') }}">Login</span> or <span title="Sign Up" class="open-signup" href="{{ route('register') }}">Sign Up</span> to
            </div>
            @endguest
            <div class="d-flex home-action-btns">
               {{-- Create event --}}
               @auth
                  <div class="create-event">
                     <div>
                        <form action="{{ route('client.create.event') }}" id="create-event-form" method="post">
                           @csrf
                           <input type="hidden" name="event_type" class="form_event_type" value="">
                           <input type="hidden" name="event_type_custom" class="form_event_type_custom" value="">
                           <input type="hidden" name="event_date" class="form_event_date" value="">
                        </form>
                        <a title="Create Your Own Event" href="{{ route('events.create') }}" class="btn">Create Your Event</a>
                     </div>
                  </div>
               @else
                  <a href="{{ route('events.create') }}" class="btn create-event-btn">Create your Event</a>
               @endauth
               {{-- End create event --}}
               <span class="m-2">Or</span>
               {{-- Create Program --}}
               <div class="create-program">
                  @auth
                     <a href="{{ route('client.program.create') }}" class="btn home-create-program-btn" style="background-color: #F58C1C">Create Your Event Program</a>
                  @else
                     <a href="{{ route('client.create.program') }}" class="btn home-create-program-btn" style="background-color: #f88000">Create Your Event Program</a>
                  @endauth
               </div>
               {{-- End Create Program --}}
            </div>
         </div>
      </div>
   </section><!-- .banner-wrap -->
   <section class="restaurant-wrap" id="home-services-section">
      <div class="container">
         <div class="title_home offset-item">
            <h2>Featured Services</h2>
         </div>
         <div class="restaurant-sliders slick-sliders offset-item">
               <div class="restaurant-slider slick-slider" data-autoplay="true" data-item="4" data-itemScroll="1" data-arrows="true" data-dots="true" data-tabletItem="2" data-tabletScroll="2" data-mobileItem="1" data-mobileScroll="1">
                  @foreach ($services as $service)
                     <livewire:home-service-view :service="$service" />
                  @endforeach
               </div><!-- .restaurant-slider -->
               <div class="place-slider__nav slick-nav">
                  <div class="place-slider__prev slick-nav__prev">
                     <i class="las la-angle-left"></i>
                  </div><!-- .place-slider__prev -->
                  <div class="place-slider__next slick-nav__next" id="carouselClick">
                     <i class="las la-angle-right"></i>
                  </div><!-- .place-slider__next -->
               </div><!-- .place-slider__nav -->
         </div><!-- .restaurant-sliders -->
      </div>
   </section><!-- .restaurant-wrap -->
   <section class="blogs-wrap section-bg">
      <div class="container">
         <div class="title_home offset-item">
            <h2>Browse Categories</h2>
         </div>
         <div class="row">
            @foreach ($categories->shuffle()->take(12) as $category)
               <div class="col-lg-3 col-md-4 col-sm-12 mb-4">
                  <div class="place-item layout-02 place-hover">
                     <div class="place-inner">
                        <div class="place-thumb hover-img">
                           <a class="entry-thumb" href="{{ route('client.services.all', $category->name) }}">
                              <img src="{{ config('services.app_url.admin_url').'/storage/category/'.$category->image  }}" alt="">
                              <span id="category-name">
                                 {{ $category->name }}
                              </span>
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            @endforeach
         </div>
      </div>
   </section><!-- .blogs-wrap -->
   @if ($testimonials->count())
      <section class="home-testimonials testimonials">
         <div class="container">
            <div class="title_home offset-item">
               <h2>Testimonials</h2>
            </div>
            <div class="testimonial-sliders slick-sliders offset-item">
               <div class="testimonial-slider slick-slider" data-item="2" data-itemScroll="2" data-arrows="true" data-dots="true" data-tabletItem="1" data-tabletScroll="1" data-mobileItem="1" data-mobileScroll="1">
                  @foreach ($testimonials as $testimonial)
                     <div class="item">
                        <div class="testimonial-item flex">
                           <div class="testimonial-thumb">
                              <img src="{{ $testimonial->user->getAvatar($testimonial->user->avatar) }}" alt="{{ $testimonial->user->f_name }}" class="avatar">
                              <img src="{{ asset('assets/assets/quote-active.png') }}" alt="quote" class="quote">
                           </div>
                           <div class="testimonial-info">
                              <p>
                                 {{ $testimonial->review }}
                              </p>
                              <div class="cite">
                                 <h4>{{ $testimonial->user->f_name }} {{ $testimonial->user->l_name }}</h4>
                              </div>
                           </div>
                        </div>
                     </div>
                  @endforeach
               <div class="place-slider__nav slick-nav">
                  <div class="place-slider__prev slick-nav__prev">
                     <i class="las la-angle-left"></i>
                  </div><!-- .place-slider__prev -->
                  <div class="place-slider__next slick-nav__next">
                     <i class="las la-angle-right"></i>
                  </div><!-- .place-slider__next -->
               </div><!-- .place-slider__nav -->
            </div><!-- .testimonial-sliders -->
         </div>
      </section><!-- .testimonials -->
   @endif
   <section class="join-wrap" style="background-image: url({{ asset('assets/images/bg/bg-join-us-2.jpg') }});">
      <div class="container">
         <div class="join-inner">
               <h2 class="offset-item">Vendors Join Us</h2>
               <p class="offset-item">Join the more than 10,000 vendors at Tupange.</p>
               @auth
                  @if (auth()->user()->isVendor() && auth()->user()->status === 'user')
                     <a title="Switch to Your Vendor Profile" class="btn offset-item" href="{{ route('switch_profile') }}">
                           <span>Switch To Vendor</span>
                     </a>
                  @elseif(auth()->user()->isVendor() && auth()->user()->status === 'vendor')
                     <a title="Switch to Your Vendor Profile" class="btn offset-item" href="{{ route('switch_profile') }}">
                           <span>Switch To Client</span>
                     </a>
                  @else
                     <a title="Create Your Vendor Profile" class="btn offset-item" href="{{ route('vendor.complete') }}">
                           <span>Become a Vendor</span>
                     </a>
                  @endif
               @else
                  <a title="Sign Up" class="btn offset-item open-signup" id="sign-up" href="{{ route('register') }}">Register</a>
               @endauth
         </div>
      </div>
   </section><!-- .join-wrap -->
  {{-- @include('partials.onboarding') --}}
   @push('scripts')
      <script>
         // setTimeout(() => {
         //    $('#onboarding-modal').modal('show')
         // }, 2000);

         const show_login_form = {!! json_encode(session()->get('event-user-login')) !!}

         if (show_login_form) {
            $(document).ready(function () {
               setTimeout(() => {
                  $('.open-login').click()
                  {!! session()->forget('event-user-login') !!}
               }, 500);
            })
         }

         function hideLeadingMessage() {
            $('#leading-message').addClass('d-none')
            setTimeout(() => {
               $('.initial-slide').removeClass('d-none')
            }, 1000);
         }

         $('.select-date').on('click', function() {
            $(this).parent().parent('.date-types').parent('.search-suggestions').parent('.field-input').children('.open-suggestion').attr('hidden', 'hidden')
            $(this).parent().parent('.date-types').parent('.search-suggestions').attr('hidden', 'hidden')
            $(this).parent().parent('.date-types').attr('hidden', 'hidden')
            $('.date-select').removeAttr('hidden')
         })

         $('.select-month').on('click', function() {
            $(this).parent().parent('.date-types').parent('.search-suggestions').parent('.field-input').children('.open-suggestion').attr('hidden', 'hidden')
            $(this).parent().parent('.date-types').parent('.search-suggestions').attr('hidden', 'hidden')
            $(this).parent().parent('.date-types').attr('hidden', 'hidden')
            $('.month-select').removeAttr('hidden')
         })

         function getEventType(e) {
            if (e == 'Other') {
               $('.custom-event-input').removeAttr('hidden')
               $('#event_type_custom').focus()
            } else {
               $('.custom-event-input').attr('hidden', 'hidden')
            }
         }

         $('#mobile-select-event-type').on('change', function(e) {
            let event_type = $(this).val()
            if (event_type == 'Other') {
               $('#event_type_custom_mobile').removeAttr('hidden', 'hidden')
               $('#event_type_custom_mobile').focus()
            } else {
               $('#event_type_custom_mobile').attr('hidden')
            }
         })

         $('#mobile-select-date').on('change', function(e) {
            e.preventDefault()
            let selectDateOption = $(this).val()
            if (selectDateOption == 'select-date') {
               $(this).attr('hidden', 'hidden')
               $(this).parent().children('.mobile-date-select').removeAttr('hidden')
            } else if(selectDateOption == 'select-month'){
               $(this).attr('hidden', 'hidden')
               $(this).parent().children('.mobile-month-select').removeAttr('hidden')
            }
         })

         var categories = ''
         $('.category').on('click', function(e) {
            e.preventDefault();
            var category = $(this).children('.category-name').text()
            if (categories.includes(category)) {
               return
            }
            categories += category+', '
            // categories = categories.replace(/,\s*$/, "")
            $('.selected-categories').text(categories)

            $('.show-selected-categories').removeAttr('hidden')
         })

         $('#mobile-submit-search').on('click', function (e) {
            e.preventDefault()
            $('.mobile-search').submit()
         })

         $('.submit-search').on('click', function(e) {
            e.preventDefault()
            categories = categories.replace(/,\s*$/, "")
            $('.category-search').children('.field-input').children('.category').val(categories)
            $('.service-search').submit()
         })
         var today = new Date();
         var dd = today.getDate();
         var mm = today.getMonth()+1; //January is 0 so need to add 1 to make it 1!
         var yyyy = today.getFullYear();
         if(dd<10){
            dd='0'+dd
         }
         if(mm<10){
            mm='0'+mm
         }

         today = yyyy+'-'+mm+'-'+dd;

         $('.date-select').attr('min', today)

         $('.home-create-event-btn').on('click', function(e) {
            e.preventDefault()
            let event_type = $('#event_type').val()
            let event_month = $('.month-select').val()
            let event_date = $('.date-select').val()
            let event_type_custom = $('#event_type_custom').val()
            if (event_month != '' || event_date != '' || event_type != '') {
               $('.form_event_type').val(event_type)
               $('.form_event_type_custom').val(event_type_custom)
               event_month != '' ? $('.form_event_date').val(event_month) : $('.form_event_date').val(event_date)
               $('#create-event-form').submit()
            } else {
               $('#create-event-form').submit()
            }
         })

         var dateClass='.date-select';

         // if (document.querySelector(dateClass).type == 'date')
         // {
         //    var oCSS = document.createElement('link');
         //    oCSS.type='text/css'; oCSS.rel='stylesheet';
         //    oCSS.href='//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.css';
         //    oCSS.onload=function()
         //    {
         //       var oJS = document.createElement('script');
         //       oJS.type='text/javascript';
         //       oJS.src='//ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js';
         //       oJS.onload=function()
         //       {
         //       $(dateClass).datepicker();
         //       }
         //       document.body.appendChild(oJS);
         //    }
         // }
      </script>
   @endpush

@endsection

