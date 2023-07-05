@extends('layouts.master')
@section('title', 'Cart')
@section('css')
   <style>
      @media only screen and (min-width: 767px) {
         #order_message {
            margin-bottom: -48px;
         }
      }
   </style>
@endsection
@section('content')
<main id="main" class="site-main">
    <div class="site-content owner-content">
        <div class="container">
            <div class="member-place-wrap">
                <div class="member-wrap-top">
                    <h2>My Cart</h2>
                </div><!-- .member-wrap-top -->
                <livewire:client-cart-view />
            </div><!-- .member-place-wrap -->
        </div>
    </div><!-- .site-content -->
</main><!-- .site-main -->
@push('scripts')
   <script>
      window.onbeforeunload = function () {
         $('#submit-cart').attr('disabled', 'disabled').val('Saving...')
      }

      function checkIfEmailInString(text) {
         var re = /(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))/;
         return re.test(text.replace(/ /g,''));
      }

      function checkIfPhoneNumberInString(text) {
         var phoneExp = /(?:(?:\+?1\s*(?:[.-]\s*)?)?(?:\(\s*([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9])\s*\)|([2-9]1[02-9]|[2-9][02-8]1|[2-9][02-8][02-9]))\s*(?:[.-]\s*)?)?([2-9]1[02-9]|[2-9][02-9]1|[2-9][02-9]{2})\s*(?:[.-]\s*)?([0-9]{4})(?:\s*(?:#|x\.?|ext\.?|extension)\s*(\d+))?/img;
         return phoneExp.test(text.replace(/ /g,''))
      }
      // let messages = document.querySelectorAll('#order_message')
      // $('.submit-cart-btn').on('click', function(e) {
      //    e.preventDefault()
      //    messages.forEach(message => {
      //       if (message.value != '') {
      //          if (checkIfEmailInString(message.value) || checkIfPhoneNumberInString(message.value)) {
      //             toastr.options =
      //                {
      //                   "closeButton" : true,
      //                   "progressBar" : true,
      //                   "positionClass" : "toast-bottom-right"
      //                }
      //             toastr.error("You cannot share emails or phone numbers!!");

      //             return
      //          }
      //       }
      //    });
      //    $('.submit-cart-form').submit()
      // })
   </script>
@endpush
@endsection
