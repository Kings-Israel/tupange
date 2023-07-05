@extends('layouts.master')
@section('title', 'Cart')
@section('css')
<style>
   .member-place-list.owner-booking td:nth-child(2) {
      width: 100%;
   }
   .cart-service-description {
      width: 75%;
   }
   @media only screen and (max-width: 998px) {
      .cart-service-description {
         width: 100%;
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
               <div>
                  @if (session('cart'))
                  <p class="error">* Please note you will need to login before creating the order(s)</p>
                     <table class="member-place-list owner-booking table-responsive">
                        <thead>
                           <tr>
                             {{-- <th>#</th> --}}
                              <th class="cart-service-description">Service</th>
                              <th>Added To Cart</th>
                              <th>Select Quote</th>
                              {{-- <td class="table-width-250">Custom Message</td> --}}
                              <th>Actions</th>
                           </tr>
                        </thead>
                        <form action="{{ route('client.checkout') }}" method="post">
                           @csrf
                           <tbody>
                                 @foreach ($services as $service)
                                    <tr>
                                       <input hidden type="checkbox" name="service[{{ $service->id }}]" id="{{ $service->id }}" value="{{ $service->id }}" checked>
                                       <td hidden>
                                         {{-- <input type="checkbox" name="service[{{ $service->id }}]" id="{{ $service->id }}" value="{{ $service->id }}"> --}}
                                       </td>
                                       <td data-title="Service" class="cart-service-description">
                                             <a href="{{ route('client.service.one', $service) }}">
                                                <b>{{ $service->service_title }}</b>
                                             </a>
                                             <p class="cart-service-description">{{ $service->service_description }}</p>
                                       </td>
                                       <td data-title="Date added">
                                          {{ session('cart')[$service->id]['added_on']->diffForHumans() }}
                                       </td>
                                       <td data-title="Pricing">
                                          <select name="service_pricing[{{ $service->id }}]" id="" class="form-control">
                                             <option value="">Get Quote</option>
                                             @if ($service->service_pricing->count())
                                                @foreach ($service->service_pricing as $pricing)
                                                   <option value="{{ $pricing->id }}" @if (session('cart')[$service->id]['pricing'] == $pricing->id) selected @endif>{{ $pricing->service_pricing_title }} (Ksh.{{ $pricing->service_pricing_price }})</option>
                                                @endforeach
                                             @endif
                                          </select>
                                       </td>
                                       <td class="table-width-250 d-none" data-title="Message">
                                          <textarea name="order_message[{{ $service->id }}]" class="form-control" hidden></textarea>
                                       </td>
                                       <td>
                                          {{-- <span class="delete remove-from-cart" data-id="{{ $service->id }}" title="Delete"><i class="la la-trash-alt"></i></span> --}}
                                          <button class="btn delete remove-from-cart" data-id="{{ $service->id }}" title="Delete">Remove</button>
                                       </td>
                                    </tr>
                                 @endforeach
                           </tbody>
                        </table>
                        <input type="submit" value="Send" class="btn" style="float: right">
                     </form>
                  @else
                     <p>No Services found in cart.</p>
                  @endif
               </div>
           </div>
       </div>
   </div>
</main>
@push('scripts')
   <script>
      $(".remove-from-cart").click(function (e) {
            e.preventDefault();

            var ele = $(this);

            if(confirm("Are you sure")) {
                $.ajax({
                    url: '{{ route('client.remove-from-cart') }}',
                    method: "DELETE",
                    data: {_token: '{{ csrf_token() }}', id: ele.attr("data-id")},
                    success: function (response) {
                        window.location.reload();
                    }
                });
            }
        });
   </script>
@endpush
@endsection
