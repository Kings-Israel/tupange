<footer id="footer" class="footer">
    <div class="container">
        <div class="footer__top">
            <div class="row">
                <div class="col-lg-5">
                    <div class="footer__top__info">
                        <a title="Logo" href="#" class="footer__top__info__logo"><img src="{{ asset('assets/images/assets/logo-1.png') }}" alt="Tupange"></a>
                        <p class="footer__top__info__desc">We want to help you with your event planning or services hunt that is why we provide you this platform. We believe you should have peace of mind when hiring service providers for your event so we give you a cash back guarantee on disputed cases. Create an account and start using our tools. Contact us if you need assistance.</p>
                    </div>
                </div>
                <div class="col-lg-2">
                    <aside class="footer__top__nav">
                        <h3>Company</h3>
                        <ul>
                            <li><a title="About Us" href="{{ url('/') }}">Home</a></li>
                            <li><a title="Blog" href="{{ route('about') }}">About</a></li>
                            <li><a title="Faqs" href="{{ route('faq') }}">Faqs</a></li>
                            {{-- <li><a title="Leave a comment" data-bs-toggle="modal" data-bs-target="#dispute" href="#">Leave a comment</a></li> --}}
                            <li><a title="Resolution Center" href="{{ route('resolution-center.index') }}">Resolution Center</a></li>
                        </ul>
                    </aside>
                </div>

                <div class="col-lg-3">
                    <aside class="footer__top__nav footer__top__nav--contact">
                        <h3>Contact Us</h3>
                        <p>Email: Tupangeevents@gmail.com</p>
                        <p>Phone: 0700776655</p>
                        <p>FCB, MIHRAB MEZZANINE 2</p>
                        <ul>
                            <li class="facebook">
                                <a title="Facebook" href="https://www.facebook.com/tupangeevents/" target="_blank">
                                    <i class="la la-facebook-f"></i>
                                </a>
                            </li>
                        </ul>
                    </aside>
                </div>
                <div class="col-lg-2">
                  <aside class="footer__top__nav">
                      <ul>
                          <li><a title="Privacy Policy" href="#" data-bs-toggle="modal" data-bs-target="#privacy-policy">Privacy policy</a></li>
                          <li><a title="Terms of use" href="#" data-bs-toggle="modal" data-bs-target="#usage-terms">Terms of Use</a></li>
                          @auth
                          <li><a title="Leave a review" href="#" data-bs-toggle="modal" data-bs-target="#customer-review">Rate Tupange.com</a></li>
                              @if (auth()->user()->orders()->count() > 5)
                              @endif
                          @endauth
                      </ul>
                  </aside>
              </div>
            </div>
        </div><!-- .top-footer -->
        <div class="footer__bottom">
            <p class="footer__bottom__copyright">{{ now()->year }} &copy; <a title="Tupange Events" href="{{ url('/') }}" style="color: #8FCA27" >Tupange Events LTD</a>.All rights reserved.</p>
        </div><!-- .top-footer -->
    </div><!-- .container -->
</footer><!-- site-footer -->
