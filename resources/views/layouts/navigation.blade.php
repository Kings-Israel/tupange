
<header id="header" class="site-header">
   <div class="container-fluid">
      <div class="navigation">
         <div class="row">
               <div class="col-md-2 col-sm-2">
                  <div class="site">
                     <div class="site__menu">
                           <a title="Menu Icon" href="{{ url('/') }}" class="site__menu__icon">
                              <i class="las la-bars la-24-black" style="font-size: 30px; color: white"></i>
                           </a>
                           @auth
                              @if (auth()->user()->status != 'vendor')
                                 <div class="mobile__cart">
                                    <ul>
                                       <livewire:cart-notifications />
                                    </ul>
                                 </div>
                              @endif
                           @endauth
                           <div class="popup-background"></div>
                           <div class="popup popup--left">
                              <a title="Close" href="#" class="popup__close">
                                 <i class="las la-times la-24-black"></i>
                              </a><!-- .popup__close -->
                              <div class="popup__content">
                                 @guest
                                    <div class="popup__user popup__box open-form">
                                       <a title="Login" href="{{ route('login') }}" class="open-login">Login</a>
                                       <a title="Sign Up" href="{{ route('register') }}" class="open-signup">Sign Up</a>
                                    </div><!-- .popup__user -->
                                    <div class="popup__menu popup__box">
                                       <ul class="menu-arrow">
                                          <li>
                                             <a href="{{ route('home') }}" title="Home">Home</a>
                                          </li>
                                          <li>
                                             <a href="{{ route('client.services.all') }}" title="Services">Services</a>
                                          </li>
                                          <li>
                                             <a href="{{ route('about') }}" title="About">About</a>
                                          </li>
                                          <li>
                                             <a title="FAQ" href="{{ route('faq') }}">FAQ</a>
                                          </li>
                                       </ul>
                                    </div><!-- .popup__menu -->
                                 @else
                                    @if (auth()->user()->isVendor() && auth()->user()->status == 'vendor')
                                       <div class="popup__menu popup__box">
                                          <ul class="menu-arrow">
                                             <li>{{ Auth::user()->f_name }} {{ Auth::user()->l_name }}</li>
                                             <li><a href="{{ route('vendor.dashboard') }}" class="@if(Route::currentRouteName() == 'vendor.dashboard') active @endif">Dashboard</a></li>
                                             <li><a href="{{ route('vendor.orders.all') }}" class="@if(Route::currentRouteName() == 'vendor.orders.all') active @endif">Orders</a></li>
                                             <li><a href="{{ route('vendor.services.all') }}" class="@if(Route::currentRouteName() == 'vendor.services.all') active @endif">Services</a></li>
                                             <li><a href="{{ route('vendor.profile') }}" class="@if(Route::currentRouteName() == 'vendor.profile') active @endif">Vendor Profile</a></li>
                                             <li>
                                                <a href="{{ route('messages') }}" class="@if(Route::currentRouteName() == 'messages') active @endif">
                                                   Chats
                                                   @if (auth()->user()->hasUnreadMessages())
                                                      <span>
                                                         {{-- <i class="fas fa-circle" style="color: #8FCA27; font-size: 8px;"></i> --}}
                                                         <span class="unread-messages-count">{{ auth()->user()->hasUnreadMessages()['count'] }}</span>
                                                      </span>
                                                   @endif
                                                </a>
                                             </li>
                                             {{-- <livewire:cart-notifications /> --}}
                                          </ul>
                                       </div>
                                    @else
                                       <div class="popup__menu popup__box">
                                          <ul class="menu-arrow">
                                             <li style="color: #000">{{ Auth::user()->name }}</li>
                                             <li>
                                                <a title="Events" class="@if(Route::currentRouteName() == 'events.index') active @endif" href="{{ route('events.index') }}">My Events</a>
                                             </li>
                                             <li>
                                                <a title="Programs" href="{{ route('client.programs.index') }}" class="@if(Route::currentRouteName() == 'client.programs.index') active @endif">My Programs</a>
                                             </li>
                                             <li>
                                                <a href="{{ route('client.services.all') }}" class="@if(Route::currentRouteName() == 'client.services.all') active @endif" title="Services">Services</a>
                                             </li>
                                             <li>
                                                <a title="Orders" href="{{ route('client.orders') }}" class="@if(Route::currentRouteName() == 'client.orders') active @endif">Orders</a>
                                             </li>
                                             <li>
                                                <a title="Chats" href="{{ route('messages') }}" class="@if(Route::currentRouteName() == 'messages') active @endif">
                                                   Chats
                                                   @if (auth()->user()->hasUnreadMessages())
                                                      <span>
                                                         {{-- <i class="fas fa-circle" style="color: #8FCA27; font-size: 8px;"></i> --}}
                                                         <span class="unread-messages-count">{{ auth()->user()->hasUnreadMessages()['count'] }}</span>
                                                      </span>
                                                   @endif
                                                </a>
                                             </li>
                                             <livewire:client-notifications />
                                             <livewire:cart-notifications />
                                          </ul>
                                       </div>
                                    @endif
                                    <div class="popup__menu popup__box">
                                       <ul class="menu-arrow">
                                          <li>
                                             <a href="#" title="Profile" class="user-profile-avatar">
                                                <img src="{{ auth()->user()->getAvatar(auth()->user()->avatar) }}" alt="mdo">
                                             </a>
                                             <ul class="sub-menu">
                                                <li><a href="{{ route('user.profile.edit') }}" title="Edit Profile">Edit Profile</a></li>
                                                @if(Auth::user()->favorites->count() > 0)
                                                   <li>
                                                      <a href="{{ route('client.favorites') }}">Favorite Services</a>
                                                   </li>
                                                @endif

                                                @if (Auth::user()->status === 'user' && Auth::user()->hasAssignedRoles())
                                                   <li>
                                                      <a href="{{ route('client.events.roles') }}">
                                                         Events With Roles
                                                      </a>
                                                   </li>
                                                @endif
                                                @if (Auth::user()->phone_verified_at == null)
                                                   <li>
                                                      <a class="text-danger" href="{{ route('verify.phone') }}" title="Verify to receive SMS notifications">
                                                         Verify Phone Number
                                                      </a>
                                                   </li>
                                                @endif
                                                <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout">Logout</a>
                                                </li>
                                             </ul>
                                          </li>
                                       </ul>
                                    </div>
                                 @endguest
                              @auth
                                 <div class="right-header__button btn">
                                    @if (auth()->user()->isVendor() && auth()->user()->status === 'user')
                                       <a title="Switch to Your Vendor Profile" href="{{ route('switch_profile') }}">
                                          <span>Switch To Vendor</span>
                                       </a>
                                    @elseif(auth()->user()->isVendor() && auth()->user()->status === 'vendor')
                                       <a title="Switch to Your Vendor Profile" href="{{ route('switch_profile') }}">
                                          <span>Switch To Client</span>
                                       </a>
                                    @else
                                       <a title="Create Your Vendor Profile" href="{{ route('vendor.complete') }}">
                                          <span>Become a Vendor</span>
                                       </a>
                                    @endif
                                 </div><!-- .right-header__button -->
                              @endauth
                           </div><!-- .popup__content -->
                        </div><!-- .popup -->
                     </div><!-- .site__menu -->
                     <div class="site__brand" id="desktop">
                        @auth
                           @if(auth()->user()->status == 'user')
                              <a title="Tupange" href="{{ url('/') }}" class="site__brand__logo"><img src="{{ asset('assets/images/assets/logo-1.png') }}" alt="Tupange"></a>
                           @else
                              <a title="Tupange" href="{{ url('/vendor/dashboard') }}" class="site__brand__logo"><img src="{{ asset('assets/images/assets/logo-1.png') }}" alt="Tupange"></a>
                           @endif
                        @endauth
                        @guest
                           <a title="Tupange" href="{{ url('/') }}" class="site__brand__logo"><img src="{{ asset('assets/images/assets/logo-1.png') }}" alt="Tupange"></a>
                        @endguest
                     </div><!-- .site__brand -->
                  </div><!-- .site -->
               </div><!-- .col-md-6 -->
               <div class="col-md-10 col-sm-10">
                  <div class="right-header align-right">
                     <livewire:nav-search />
                     <nav class="main-menu">
                        <ul>
                           @guest
                              <li>
                                 <a href="{{ route('home') }}" class="@if(Route::currentRouteName() == 'home') active @endif" title="Home">Home</a>
                              </li>
                              <li>
                                 <a href="{{ route('client.services.all') }}" class="@if(Route::currentRouteName() == 'client.services.all') active @endif" title="Services">Services</a>
                              </li>
                              <li>
                                 <a href="{{ route('about') }}" class="@if(Route::currentRouteName() == 'about') active @endif" title="About">About</a>
                              </li>
                              <li>
                                 <a title="Support" href="{{ route('faq') }}" class="@if(Route::currentRouteName() == 'faq') active @endif">FAQ</a>
                              </li>
                              @if (Route::has('login'))
                                 <li>
                                    <a title="Login" class="open-login" href="{{ route('login') }}">Login</a>
                                 </li>
                              @endif

                              @if (Route::has('register'))
                                 <li>
                                    <a title="Sign Up" class="open-signup" href="{{ route('register') }}">Register</a>
                                 </li>
                              @endif
                              <li>
                                 <a title="Cart" href="{{ route('client.user.cart') }}"><i class="fa fa-shopping-cart" style="font-size: 12px; cursor: pointer"></i> ({{ count((array) session('cart')) }})</a>
                              </li>
                           @else
                              @if (auth()->user()->isVendor() && auth()->user()->status == 'vendor')
                                 <li><a title="Dashboard" class="@if(Route::currentRouteName() == 'vendor.dashboard') active @endif" href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                                 <li><a title="Orders" class="@if(Route::currentRouteName() == 'vendor.orders.all') active @endif" href="{{ route('vendor.orders.all') }}">Orders</a></li>
                                 <li><a title="Services" class="@if(Route::currentRouteName() == 'vendor.services.all') active @endif" href="{{ route('vendor.services.all') }}">Services</a></li>
                                 <li><a title="Vendor Profile" class="@if(Route::currentRouteName() == 'vendor.profile') active @endif" href="{{ route('vendor.profile') }}">Vendor Profile</a></li>
                                 <li>
                                    <a title="Chats" href="{{ route('messages') }}" class="@if(Route::currentRouteName() == 'messages') active @endif">
                                       Chats
                                       @if (auth()->user()->hasUnreadMessages())
                                          <span>
                                             {{-- <i class="fas fa-circle" style="color: #8FCA27; font-size: 8px;"></i> --}}
                                             <span class="unread-messages-count">{{ auth()->user()->hasUnreadMessages()['count'] }}</span>
                                          </span>
                                       @endif
                                    </a>
                                 </li>
                                 {{-- <livewire:cart-notifications /> --}}
                              @else
                                 <li>
                                       <a title="Events" class="@if(Route::currentRouteName() == 'events.index') active @endif" href="{{ route('events.index') }}">My Events</a>
                                 </li>
                                 <li>
                                    <a title="Programs" class="@if(Route::currentRouteName() == 'client.programs.index') active @endif" href="{{ route('client.programs.index') }}">My Programs</a>
                                 </li>
                                 <li>
                                       <a href="{{ route('client.services.all') }}" class="@if(Route::currentRouteName() == 'client.services.all') active @endif" title="Services">Services</a>
                                 </li>
                                 <li>
                                    <a title="Orders" class="@if(Route::currentRouteName() == 'client.orders') active @endif" href="{{ route('client.orders') }}">Orders</a>
                                 </li>
                                 <li>
                                    <a title="Chats" href="{{ route('messages') }}" class="@if(Route::currentRouteName() == 'messages') active @endif">Chats</a>
                                    @if (auth()->user()->hasUnreadMessages())
                                       <span>
                                          {{-- <i class="fas fa-circle" style="color: #8FCA27; font-size: 8px;"></i> --}}
                                          <span class="unread-messages-count">{{ auth()->user()->hasUnreadMessages()['count'] }}</span>
                                       </span>
                                    @endif
                                 </li>
                                 <livewire:client-notifications />
                                 <livewire:cart-notifications />
                              @endif
                              <li>
                                 <a href="#" class="user-profile-avatar">
                                    <img src="{{ auth()->user()->getAvatar(auth()->user()->avatar) }}" alt="mdo">
                                 </a>
                                 <ul class="sub-menu">
                                    <li style="color: #000">{{ Auth::user()->f_name }} {{  Auth::user()->l_name }}</li>
                                    <hr>
                                    <li><a href="{{ route('user.profile.edit') }}" title="Edit Profile">Edit Profile</a></li>
                                    @if (Auth::user()->status == 'user' && Auth::user()->hasAssignedRoles())
                                       <li>
                                          <a href="{{ route('client.events.roles') }}">
                                             Events With Roles
                                          </a>
                                       </li>
                                    @endif
                                    @if (Auth::user()->phone_verified_at == null)
                                       <li>
                                          <a class="text-danger" href="{{ route('verify.phone') }}" title="Verify to receive SMS notifications">
                                             Verify Phone Number
                                          </a>
                                       </li>
                                    @endif
                                    @if(Auth::user()->favorites->count() > 0)
                                       <li>
                                          <a href="{{ route('client.favorites') }}">Favorite Services</a>
                                       </li>
                                    @endif
                                    <li><a href="{{ route('logout') }}"
                                          onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();" title="Logout">Logout</a>
                                       <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                          @csrf
                                       </form>
                                    </li>
                                 </ul>
                              </li>
                           @endguest
                        </ul>
                     </nav>
                     <div class="popup popup-form">
                        <a title="Close" href="#" class="popup__close">
                           <i class="las la-times la-24-black"></i>
                        </a><!-- .popup__close -->
                        <ul class="choose-form">
                           <li class="nav-signup"><a title="Sign Up" href="#signup">Sign Up</a></li>
                           <li class="nav-login"><a title="Log In" href="#login">Log In</a></li>
                        </ul>
                        {{-- <p class="choose-more">Continue with <a title="Facebook" class="fb" href="{{ route('facebook.login') }}">Facebook</a> or <a title="Google" class="gg" href="{{ route('google.login') }}">Google</a></p> --}}
                        <p class="choose-more">Continue with <a title="Google" class="gg" href="{{ route('google.login') }}">Google</a></p>
                        <p class="choose-or"><span>Or</span></p>
                        <div class="popup-content">
                        <form class="form-sign form-content" id="signup" method="POST" action="{{ route('register') }}" onsubmit="return validateRecaptcha(event)">
                              @csrf
                              <div class="field-inline">
                                 <div class="field-input">
                                    <input id="f_name" type="text"  placeholder="First Name" name="f_name" class="form-control @error('f_name') is-invalid @enderror" autocomplete="off" required>
                                    <span id="f_nameError">
                                       <strong style="color: red"></strong>
                                    </span>
                                 </div>
                                 <div class="field-input">
                                    <input id="l_name" type="text"  placeholder="Last Name" name="l_name" class="form-control @error('l_name') is-invalid @enderror" autocomplete="off" required>
                                    <span id="l_nameError">
                                       <strong style="color: red"></strong>
                                    </span>
                                 </div>
                              </div>
                              <div class="field-input">
                                 <input placeholder="Phone number"  type="text" class="form-control @error('phone_number') is-invalid @enderror" name="phone_number" autocomplete="off" required>
                                 <span id="phone_numberError">
                                    <strong style="color: red"></strong>
                                 </span>
                              </div>
                              <div class="field-input">
                                 <input type="email" placeholder="Email" value="" name="email" autocomplete="off" required>
                                 <span id="emailError">
                                    <strong style="color: red"></strong>
                                 </span>
                              </div>
                              <div class="field-input">
                                 <input type="password" placeholder="Password" value="" name="password" id="passwordbox-id"  @error('password') is-invalid @enderror autocomplete="off">
                                 <span id="passwordError">
                                    <strong style="color: red"></strong>
                                 </span>
                              </div>
                              <p style="color: #000; float: right; cursor: pointer;" id="show-password" onclick="passwordShow(this.id)">Show</p>
                              <p style="color: #000; float: right; cursor: pointer;" hidden id="hide-password" onclick="passwordShow(this.id)">Hide</p>

                              <div class="field-input">
                                 <input type="password" placeholder="Password confirmation" value="" name="password_confirmation" id="passwordconfirmbox-id" autocomplete="off" required>
                              </div>

                              <div class="form-group row {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}" style="padding-bottom:20px">
                                 <label for="captcha" class="col-form-label text-md-right">{{ __('Captcha') }}</label>
                                 <div class="col-md-6">
                                 <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}"></div>
                                 <!-- @if ($errors->has('g-recaptcha-response'))
                                 <span class="help-block">
                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                 </span>
                                 @endif -->
                                 </div>
                              </div>

                              <input type="submit" name="submit" value="Register" id="register-button">
                           </form>
                           <form method="POST" action="{{ route('login') }}" class="form-log form-content" id="login">
                              @csrf
                              <div class="field-input">
                                    <input type="email"  placeholder="Email" value="" name="email" required>
                              </div>
                              <div class="field-input">
                                    <input type="password" placeholder="Password" value="" name="password" required>
                              </div>
                              <h5 id="error-message" style="color: red; text-align: center; display: none">Invalid Credentials</h5>
                              <a title="Forgot password" class="forgot_pass" href="{{ url('/forgot-password') }}">Forgot password</a>
                              <input type="submit" name="submit" id="login-button" value="Login">
                           </form>
                        </div>
                     </div><!-- .popup-form -->
                     @auth
                        <div class="right-header__button btn">
                           @if (auth()->user()->isVendor() && auth()->user()->status === 'user')
                              <a title="Switch to Your Vendor Profile" href="{{ route('switch_profile') }}">
                                    <span>Switch To Vendor</span>
                              </a>
                           @elseif(auth()->user()->isVendor() && auth()->user()->status === 'vendor')
                              <a title="Switch to Your Vendor Profile" href="{{ route('switch_profile') }}">
                                    <span>Switch To Client</span>
                              </a>
                           @else
                              <a title="Create Your Vendor Profile" href="{{ route('vendor.complete') }}">
                                    <span>Register as Vendor</span>
                              </a>
                           @endif
                        </div><!-- .right-header__button -->
                     @endauth
                  </div><!-- .right-header -->
               </div><!-- .col-md-6 -->
         </div><!-- .row -->
      </div><!-- .container-fluid -->
   </div>
@push('scripts')
<script>
        function passwordShow(id) {
         var x = document.getElementById("passwordbox-id");
         var x_confirm = document.getElementById("passwordconfirmbox-id");
         if (x.type === "password") {
            x.type = "text";
         } else {
            x.type = "password";
         }
         if (x_confirm.type === "password") {
            x_confirm.type = "text";
         } else {
            x_confirm.type = "password";
         }
         if (id == 'show-password') {
            document.getElementById('show-password').setAttribute("hidden", "hidden")
            document.getElementById('hide-password').removeAttribute('hidden')
         } else if(id == 'hide-password') {
            document.getElementById('hide-password').setAttribute('hidden', 'hidden')
            document.getElementById('show-password').removeAttribute('hidden')
         }
      }
   </script>
   <script>
   function validateForm(event) {
      // Prevent the form from submitting automatically
      event.preventDefault();

      // Check if the reCAPTCHA response is filled
      var response = grecaptcha.getResponse();
      if (response.length === 0) {
         // reCAPTCHA is not filled, display an error message
         alert("Please complete the reCAPTCHA.");
         return;
      }

      // If the reCAPTCHA is filled, submit the form
      document.getElementById("signup").submit();
   }
</script>



   <script src="https://www.google.com/recaptcha/api.js" async defer></script>
   <script>
      function validateRecaptcha(event) {
      var response = grecaptcha.getResponse();
      if (response.length === 0) {
         // reCAPTCHA is not filled, prevent form submission
         event.preventDefault();
         alert("Please complete the reCAPTCHA.");
      } else {
         // reCAPTCHA is filled, allow form submission
         return true;
      }
   }


    function recaptchaCallback(token) {
         document.getElementById('recaptchaResponse').value = token;
         document.getElementById('recaptchaError').textContent = '';
      }

      function recaptchaExpired() {
         document.getElementById('recaptchaResponse').value = '';
         document.getElementById('recaptchaError').textContent = 'reCAPTCHA verification expired. Please try again.';
      }

      document.getElementById('register-button').addEventListener('click', function(event) {
         if (document.getElementById('recaptchaResponse').value === '') {
            event.preventDefault();
            toastr.options = {
               "closeButton": true,
               "progressBar": true,
               "positionClass": "toast-bottom-right"
            };
            toastr.error('Please complete the reCAPTCHA verification.');
         }
      });

   </script>


@endpush
</header><!-- .site-header -->

