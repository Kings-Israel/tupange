@extends('layouts.master')
@section('title', 'Dashboard')
@section('css')
<style>
   .item h3:hover {
      color: #1DA1F2;
   }
</style>
@endsection
@section('content')
<main id="main" class="site-main vendor-dashboard">
    <div class="site-content owner-content">
        <div class="container">
            <div class="member-wrap">
                <div class="member-wrap-top">
                    <h2>{{ Auth::user()->vendor->company_name }}</h2>
                </div><!-- .member-wrap-top -->
                <div class="member-statistical">
                    <div class="row">
                        <div class="col-lg-4 col-12">
                            <div class="item blue">
                              <a href="{{ route('vendor.services.all') }}">
                                 <h3>Active Services</h3>
                                 <span class="number">{{ $services_count }}</span>
                                 <span class="line"></span>
                              </a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="item green">
                              <a href="{{ route('vendor.orders.all') }}">
                                 <h3>Orders Made</h3>
                                 <span class="number">{{ $orders_count }}</span>
                                 <span class="line"></span>
                              </a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-12">
                            <div class="item yellow">
                              <a href="{{ route('vendor.reviews') }}">
                                 <h3>Total Reviews</h3>
                                 <span class="number">{{ $reviews_count }}</span>
                                 <span class="line"></span>
                              </a>
                            </div>
                        </div>
                    </div>
                </div><!-- .member-statistical -->
                <div class="owner-box">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="ob-item">
                                <div class="ob-head">
                                    <h3>Recent Orders</h3>
                                    <a href="{{ route('vendor.orders.all') }}" class="view-all" title="View All">View all</a>
                                </div>
                                <div class="ob-content">
                                    <ul>
                                        @foreach ($orders->orders as $order)
                                            <li class="pending">
                                                <p class="date"><b>Ordered:</b>{{ $order->created_at->diffForHumans() }}</p>
                                                <p class="place"><b>Service:</b>{{ $order->service->service_title }}</p>
                                                <p class=""><b>Client:</b>{{ $order->user->f_name }} {{ $order->user->l_name }}</p>
                                                <a href="{{ route('vendor.orders.one', $order) }}" title="More" class="more"><i class="las la-angle-right"></i></a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="ob-item">
                                <div class="ob-head">
                                    <h3>New Reviews</h3>
                                    <a href="{{ route('vendor.reviews') }}" class="view-all" title="View All">View all</a>
                                </div>
                                <div class="ob-content">
                                    <ul class="place__comments">
                                       @foreach ($reviews->reviews as $review)
                                          <li>
                                             <div class="place__author">
                                                <div class="place__author__avatar">
                                                      <span><img src="{{ $review->user->getAvatar($review->user->avatar) }}" alt="{{ $review->user->f_name }}"></span>
                                                </div>
                                                <div class="place__author__info">
                                                         <span>{{ $review->user->f_name }} {{ $review->user->l_name }}</span>
                                                         <div class="place__author__star">
                                                            <i class="la la-star"></i>
                                                            <i class="la la-star"></i>
                                                            <i class="la la-star"></i>
                                                            <i class="la la-star"></i>
                                                            <i class="la la-star"></i>
                                                            <span style="width: {{ ((int)$review->rating/5) * 100 }}%">
                                                               <i class="la la-star"></i>
                                                               <i class="la la-star"></i>
                                                               <i class="la la-star"></i>
                                                               <i class="la la-star"></i>
                                                               <i class="la la-star"></i>
                                                            </span>
                                                         </div>
                                                      <span class="time">{{ $review->created_at->diffForHumans() }}</span>
                                                </div>
                                             </div>
                                             <div class="place__comments__content" style="height: auto; max-height: 120px; overflow-y: auto;">
                                                <p>
                                                   {{ $review->comment }}
                                                </p>
                                             </div>
                                             <a href="{{ route('vendor.services.one', $order->service) }}">
                                                <p class="place"><b>Service:</b>{{ $review->service->service_title }}</p>
                                             </a>
                                          </li>
                                       @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                           <livewire:vendor-notification :vendor="$vendor" />
                        </div>
                    </div>
                </div><!-- .owner-box -->
            </div><!-- .member-wrap -->
        </div>
    </div><!-- .site-content -->
</main><!-- .site-main -->
@endsection
