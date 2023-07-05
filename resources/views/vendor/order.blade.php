@extends('layouts.master')
@section('title', 'Order')
@section('css')
   <style>
      .entry-thumb img{
         border-radius: 15px;
      }
      .order_quotation_agreement {
         max-height: 100px;
         overflow-x: hidden;
         overflow-y: auto;
      }
      .entry-detail {
         min-height: 200px !important;
         display: flex !important;
         flex-direction: column !important;
         justify-content: space-between !important;
      }
      .order-status-btn {
         margin-right: 3px;
      }
      @media only screen and (max-width: 992px) {
         .sidebar--shop {
            padding-left: 0;
         }
         .order-status-btn {
            margin-top: 3px !important;
         }
      }
   </style>
@endsection
@section('content')
@include('partials.create-cutom-quote')
@include('partials.vendor-cancel-order')
<main id="main" class="site-main mt-5">
   <div class="container">
      <div class="row">
         <div class="col-lg-4">
            <div class="sidebar sidebar--shop sidebar--border">
               <aside class="widget widget-shadow widget-reservation">
                  <div class="shop-details__thumb">
                     <a class="entry-thumb" href="{{ route('vendor.services.one', $order->service) }}"><img src="{{ $order->service->getCoverImage($order->service->service_image) }}" alt=""></a>
                  </div>
                  <br>
                  <a href="{{ route('vendor.services.one', $order->service) }}">
                     <h1>{{ $order->service->service_title }}</h1>
                  </a>
                  <hr>
                  <h2>Client's Order Message: </h2>
                  @if ($order->message)
                     <p>{{ $order->message }}</p>
                  @else
                     <p>No Message</p>
                  @endif
               </aside><!-- .widget-reservation -->
               </div><!-- .sidebar -->
         </div>
         <div class="col-lg-8">
               <div class="place__left">
               <h1>{{ $order->user->f_name }} {{ $order->user->l_name }}'s Order</h1>
                  <div class="place__box place__box--npd">
                     <div class="row">
                           <h1 class="col-lg-8">Order <strong>{{ $order->order_id }}</strong></h1>
                           @if (! $order->service_pricing && $order->order_quotation_id == null)
                              <div class="right-header__button btn col-lg-3" data-bs-toggle="modal" data-bs-target="#add-custom-quote">
                                 <span>Create Custom Quote</span>
                              </div>
                           @endif
                           <div class="row">
                              @if ($order->service_pricing)
                                 <div class="col-lg-4 col-md-6">
                                       <div class="place-item layout-02 place-hover">
                                          <div class="place-inner">
                                             <div class="entry-detail">
                                                   <div class="place-type list-item">
                                                      @if ($order->payment()->count() > 0)
                                                         <span style="display:flex;">Ksh. <b>{{ number_format($order->payment->amount) }}</b></span>
                                                      @else
                                                         <span style="display:flex;">Ksh. <b>{{ number_format($order->service_pricing->service_pricing_price) }}</b></span>
                                                      @endif
                                                      {{-- <span>Ksh. {{ $order->service_pricing->service_pricing_price }}</span> --}}
                                                   </div>
                                                   <h3 class="place-title" style="margin-bottom: -5px">{{ $order->service_pricing->service_pricing_title }}</h3>
                                                   <div class="package-description">
                                                      <p>{{ $order->service_pricing->service_pricing_description }}</p>
                                                   </div>
                                             </div>
                                          </div>
                                       </div>
                                 </div>
                              @elseif($order->order_quotation)
                                 <div class="col-lg-4 col-md-6">
                                       <div class="place-item layout-02 place-hover">
                                          <div class="place-inner">
                                             <div class="entry-detail">
                                                   <div class="place-type list-item">
                                                      @if ($order->payment()->count() > 0)
                                                         <span style="display:flex;">Ksh. <b>{{ number_format($order->payment->amount) }}</b></span>
                                                      @else
                                                         <span style="display: flex">Ksh. <b>{{ number_format($order->order_quotation->order_pricing_price) }}</b></span>
                                                      @endif
                                                      {{-- <span>Ksh. {{ $order->order_quotation->order_pricing_price }}</span> --}}
                                                   </div>
                                                   <h3 class="place-title" style="margin-bottom: -5px">{{ $order->order_quotation->order_pricing_title }}</h3>
                                                   <div class="package-description">
                                                      <p class="order_quotation_agreement">{{ $order->order_quotation->order_pricing_agreement }}</p>
                                                   </div>
                                                   <span></span>
                                                   <p style="color: green">{{ $order->order_quotation->status }}</p>
                                             </div>
                                          </div>
                                       </div>
                                 </div>
                              @else
                                 @forelse ($order_quotations as $order_quotation)
                                       <div class="col-lg-4 col-md-6">
                                          <div class="place-item layout-02 place-hover">
                                             <div class="place-inner">
                                                   <div class="entry-detail">
                                                      <div class="place-type list-item">
                                                         <span>Ksh. {{ $order_quotation->order_pricing_price }}</span>
                                                      </div>
                                                      <h3 class="place-title" style="margin-bottom: -5px">{{ $order_quotation->order_pricing_title }}</h3>
                                                      <p class="order_quotation_agreement">{{ $order_quotation->order_pricing_agreement }}</p>
                                                      <span></span>
                                                      <p style="color: {{ $order_quotation->status == 'Pending' ? 'purple' : 'red' }}">{{ $order_quotation->status }}</p>
                                                   </div>
                                             </div>
                                          </div>
                                       </div>
                                 @empty
                                       <h1>Get Quote</h1>
                                 @endforelse
                              @endif
                           </div>
                     </div>
                  </div>
               </div><!-- .place__left -->
               <div class="place__left">
               <div class="place__box place__box--npd">
                  <h1>Order Status</h1>
                  <div>
                     <livewire:order-status-view :order="$order" />
                     <p>{{ $order->status_reason }}</p>
                     <hr>
                     <p>Order was made:</p>
                     <h4>{{ $order->created_at->diffForHumans() }}</h4>
                  </div>
               </div>
            </div><!-- .place__left -->
            <div class="place__left">
               <div class="place__box place__box--npd">
                  <div class="d-flex justify-content-start">
                     <livewire:order-status :order="$order" />
                     @if ($order->status == 'Cancelled')
                        <a href="{{ route('vendor.order.delete', $order) }}">
                           <button class="btn order-status-btn" style="background: red">Delete Order</button>
                        </a>
                     @endif
                     <a href="{{ route('message.chat', $order->order_id) }}">
                        <button class="btn order-status-btn">Contact Client</button>
                     </a>
                  </div>
{{--                  <div class="row">--}}
{{--                     <div class="col-lg-6">--}}
{{--                        <livewire:order-status :order="$order" />--}}
{{--                     </div>--}}
{{--                     <div class="col-lg-6">--}}
{{--                        @if ($order->status == 'Cancelled')--}}
{{--                           <a href="{{ route('vendor.order.delete', $order) }}">--}}
{{--                              <button class="btn" style="background: red">Delete Order</button>--}}
{{--                           </a>--}}
{{--                        @endif--}}
{{--                        <a href="{{ route('message.chat', $order->order_id) }}">--}}
{{--                           <button class="btn">Chat with Client</button>--}}
{{--                        </a>--}}
{{--                     </div>--}}
{{--                  </div>--}}
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="owner-page-wrap">
      <div class="container">
         <div class="row">
            <div class="review-wrap">
               <h2>Reviews</h2>
               @if ($order->review)
                  <ul class="place__comments">
                     <li>
                        <div class="place__author">
                           <div class="place__author__avatar">
                              <img src="{{ $order->review->user->getAvatar($order->review->user->avatar) }}" alt="">
                           </div>
                           <div class="place__author__info">
                              <span>{{ $order->review->user->name }}</span>
                                 <div class="place__author__star">
                                       <i class="la la-star"></i>
                                       <i class="la la-star"></i>
                                       <i class="la la-star"></i>
                                       <i class="la la-star"></i>
                                       <i class="la la-star"></i>
                                       <span style="width: {{ $order->review->getStarRating($order->review->rating) }}%">
                                          <i class="la la-star"></i>
                                          <i class="la la-star"></i>
                                          <i class="la la-star"></i>
                                          <i class="la la-star"></i>
                                          <i class="la la-star"></i>
                                       </span>
                                 </div>
                                 <span class="time">{{ $order->review->created_at->diffForHumans() }}</span>
                           </div>
                        </div>
                        <div class="place__comments__content">
                           <p>{{ $order->review->comment }}</p>
                        </div>
                     </li>
                  </ul>
               @else
                  <p>No Review has been made for this order</p>
               @endif
            </div>
         </div>
      </div>
   </div>
</main><!-- .site-main -->
@endsection
