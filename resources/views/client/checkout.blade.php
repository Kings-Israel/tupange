@extends('layouts.master')
@section('title', 'Checkout')
@section('css')
<style>
   @media only screen and (min-width: 998px) {
      .payment-actions a, .payment-actions input {
         float: right;
      }
      .payment-actions a {
         margin-left: 10px;
      }
   }
   @media only screen and (max-width: 998px) {
      .payment-actions {
         display: flex;
         justify-content: space-between;
      }
      #price-view {
         float: right;
      }
   }
</style>
@endsection
@section('content')
   <div>
      <main id="main" class="site-main">
         <div class="site-content owner-content">
            <div class="container">
               <div class="member-place-wrap">
                  <div class="member-wrap-top">
                     <h2>Make Payment</h2>
                  </div><!-- .member-place-wrap -->
                  <livewire:client-checkout :orders="$orders" :price="$price" :user="$user" />
               </div><!-- .member-wrap-top -->
            </div>
         </div><!-- .site-content -->
      </main><!-- .site-main -->
   </div>
   @push('scripts')
      <script>
         function selectedPaymentMethod() {
            var method = $('#payment_method').val()
            if (method == 'Mpesa') {
               $('#phone_number_input').removeAttr('hidden')
               $('#phone_number').attr('required', true)
            } else {
               $('#phone_number_input').attr('hidden', true)
               $('#phone_number').removeAttr('required')
            }
         }
         function checkPhoneNumber(number) {
            var phoneExp = /^(?:254|\+254|0)?((?:(?:7(?:(?:[01249][0-9])|(?:5[789])|(?:6[89])))|(?:1(?:[1][0-5])))[0-9]{6})$/;
            return phoneExp.test(number.replace(/ /g,''))
         }

         let service_pricings = $('[id=service_pricing_view]')
         service_pricings.each((ind, obj) => {
            var num = obj.innerHTML.replace(/,/gi, "");
            var num2 = num.split(/(?=(?:\d{3})+$)/).join(",")
            obj.innerHTML = num2
         });
         $('#pay-btn').on('click', function(e) {
            e.preventDefault()

            var paymentMethod = $('#payment_method').val()
            var phone_number = $('#phone_number').val()

            if (paymentMethod == 'Mpesa') {
               if (phone_number == '') {
                  $('.error').removeAttr('hidden')
                  setTimeout(() => {
                     $('.error').attr('hidden', true)
                  }, 5000);
               } else if(checkPhoneNumber(phone_number) == false) {
                  toastr.options =
                     {
                        "closeButton" : true,
                        "progressBar" : true,
                        "positionClass" : "toast-bottom-right"
                     }
                  toastr.error("Please enter a valid phone number");
               } else {
                  $('#checkout-form').submit()
               }
            } else if(paymentMethod == 'Debit Card') {
               $('#checkout-form').attr('action', 'https://portal.host.iveri.com/Lite/Authorise.aspx')
               $('#checkout-form').submit()
            } else {
               $('#checkout-form').submit()
            }
         })
      </script>
   @endpush
@endsection
