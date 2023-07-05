@extends('layouts.master')
@section('title', 'Resolution Center')
@section('css')
   <style>
      .raised-issue {
         display: flex;
         justify-content: space-between
      }
      .raised-issue-details {
         display: flex;
      }
      .raised-issue img {
         width: 60px;
         height: 60px;
         margin-right: 10px;
         border-radius: 50%;
         object-fit: cover;
      }
      .raised-issue-actions {
         top: 0;
         right: 0;
      }
      .raised-issue-actions i, .raised-issue-actions p {
         background: #8FCA27;
         color: #fff;
         padding: 5px;
         border-radius: 3px;
      }
      .responses {
         background: rgb(233, 233, 233);
         padding: 15px;
         border-radius: 5px;
      }

      .response {
         display: flex;
         background: rgb(199, 199, 199);
         padding: 15px;
         border-radius: 5px;
         margin-bottom: 1px;
      }
      .responses > .admin-response {
         border: 3px solid rgb(84, 84, 84);
      }
      .responses > .admin-response img {
         border: 3px solid rgb(84, 84, 84);
      }
      .response-user-image {
         width: 60px;
         height: 60px;
         margin-right: 10px;
         border-radius: 50%;
         object-fit: cover;
      }
      .open-login, .open-signup {
         color: #23D3D3;
         cursor: pointer;
      }
      @media only screen and (min-width: 768px) {
         .mobile-heading {
            display: none;
         }
      }
      @media only screen and (max-width: 768px) {
         .response-user-image, .raised-issue img {
            display: none;
         }
         .mobile-heading {
            display: block;
            margin-top: 70px;
            text-align: center;
         }
         .desktop-heading {
            display: none;
         }
      }
   </style>
@endsection
@section('content')
<main id="main" class="site-main service-details">
   <div class="owner-page-wrap">
      <h1 class="mobile-heading">Resolution Center</h1>
      <div class="container">
         <div class="row">
            <div class="col-lg-4 col-md-5 col-sm-12">
               <div class="sidebar sidebar--shop sidebar--border">
                  <aside class="widget widget-shadow widget-reservation">
                     @auth
                        <form action="{{ route('resolution-center.store') }}" method="post" class="add-issue-form" id="add-issue-form">
                           @csrf
                           <div class="field-group field-input">
                              <label>Enter the description of your issue here</label>
                              <textarea name="issue" id="issue" class="form-control" rows="10"></textarea>
                           </div>
                           <br>
                           <input type="Submit" class="btn submit-issue" value="Submit">
                        </form>
                     @else
                        <div class="add-issue-form">
                           Please <span class="open-login" href="{{ route('login') }}">Login</span> or <span title="Sign Up" class="open-signup" href="{{ route('register') }}">Sign Up</span> to contribute
                        </div>
                     @endauth
                  </aside><!-- .widget-reservation -->
               </div><!-- .sidebar -->
            </div>
            <div class="col-lg-8 col-md-7 col-sm-12">
               <div class="place__left">
                  <div class="place__box place__box--npd">
                     <h1 class="desktop-heading">Resolution Center</h1>
                     @foreach ($issues as $issue)
                        @include('partials.resolution-center-response')
                        @include('partials.resolution-center-edit')
                        <div class="place-item layout-02 place-hover">
                           <div class="place-inner">
                              <div class="entry-detail">
                                 <div class="raised-issue">
                                    <div class="raised-issue-details">
                                       <img src="{{ $issue->user->getAvatar($issue->user->avatar) }}" alt="{{ $issue->user->f_name }}" class="avatar">
                                       <div class="package-description">
                                          <h5>{{ $issue->user->f_name }} {{ $issue->user->l_name }}</h5>
                                          <p>{{ $issue->issue }}</p>
                                          <p><strong>{{ $issue->created_at->diffForHumans() }}</strong></p>
                                       </div>
                                    </div>
                                    <div class="raised-issue-actions">
                                       @auth
                                          @if (auth()->user()->id === $issue->user_id)
                                             <i class="fas fa-pencil" id="edit-package" data-bs-toggle="modal" data-bs-target="#update-{{ $issue->id }}" style="cursor: pointer"></i>
                                          @else
                                             <p data-bs-toggle="modal" data-bs-target="#response-{{ $issue->id }}" style="cursor: pointer">Reply</p>
                                          @endif
                                       @endauth
                                    </div>
                                 </div>
                                 <hr>
                                 <div class="responses">
                                    <h3>Replies</h3>
                                    @forelse ($issue->raisedIssueResponses as $response)
                                       <div class="response @if($response->isAdminResponse) admin-response @endif">
                                          @if ($response->isAdminResponse)
                                             <img class="response-user-image" src="{{ config('services.app_url.admin_url').'/assets/images/user.png' }}" alt="">
                                          @else
                                             <img class="response-user-image" src="{{ $response->user->getAvatar($response->user->avatar) }}" alt="{{ $response->user->f_name }}" class="avatar">
                                          @endif
                                          <span class="response-text">
                                             @if (!$response->isAdminResponse)
                                                <h5>{{ $response->user->f_name }} {{ $response->user->l_name }}</h5>
                                             @endif
                                             <p>{{ $response->response }}</p>
                                             <p><strong>{{ $response->created_at->diffForHumans() }}</strong></p>
                                          </span>
                                       </div>
                                    @empty
                                       <span>No responses provided yet</span>
                                    @endforelse
                                 </div>
                              </div>
                           </div>
                        </div>
                        <br>
                     @endforeach
                  </div>
               </div><!-- .place__left -->
            </div>
         </div>
      </div>
   </div>
</main><!-- .site-main -->
@push('scripts')
   <script>
      function checkIfEmailInString(text) {
         var re = /(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/;
         return re.test(text.replace(/ /g,''));
      }

      function checkIfPhoneNumberInString(text) {
         var phoneExp = /(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?/img;
         return phoneExp.test(text.replace(/ /g,''))
      }

      $('.submit-issue').on('click', function (e) {
         e.preventDefault();
         let message = $('#issue').val()
         if (checkIfEmailInString(message)) {
            toastr.options =
               {
                  "closeButton" : true,
                  "progressBar" : true,
                  "positionClass" : "toast-bottom-right"
               }
            toastr.error("You cannot share contact information!!");

            return
         }
         if (checkIfPhoneNumberInString(message)) {
            toastr.options =
               {
                  "closeButton" : true,
                  "progressBar" : true,
                  "positionClass" : "toast-bottom-right"
               }
            toastr.error("You cannot share contact information!!");

            return
         }

         $('#add-issue-form').submit()
      })

      // $('#update-issue-submit-btn').on('click', function (e) {
      //    e.preventDefault();
      //    let message = $('.updated-issue').val()
      //    if (checkIfEmailInString(message)) {
      //       toastr.options =
      //          {
      //             "closeButton" : true,
      //             "progressBar" : true,
      //             "positionClass" : "toast-bottom-right"
      //          }
      //       toastr.error("You cannot share contact information!!");

      //       return
      //    }
      //    if (checkIfPhoneNumberInString(message)) {
      //       toastr.options =
      //          {
      //             "closeButton" : true,
      //             "progressBar" : true,
      //             "positionClass" : "toast-bottom-right"
      //          }
      //       toastr.error("You cannot share contact information!!");

      //       return
      //    }

      //    $('#update-issue-form').submit()
      // })

      // $('#issue-response-submit-btn').on('click', function (e) {
      //    e.preventDefault();
      //    let message = $('.issue-response').val()
      //    if (checkIfEmailInString(message)) {
      //       toastr.options =
      //          {
      //             "closeButton" : true,
      //             "progressBar" : true,
      //             "positionClass" : "toast-bottom-right"
      //          }
      //       toastr.error("You cannot share contact information!!");

      //       return
      //    }
      //    if (checkIfPhoneNumberInString(message)) {
      //       toastr.options =
      //          {
      //             "closeButton" : true,
      //             "progressBar" : true,
      //             "positionClass" : "toast-bottom-right"
      //          }
      //       toastr.error("You cannot share contact information!!");

      //       return
      //    }

      //    $('#issue-response-form').submit()
      // })
   </script>
@endpush
@endsection
