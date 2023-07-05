@extends('layouts.master')
@section('title', 'Orders')
@section('css')
   <style>
      #orders-filters-form {
         margin-bottom: 10px;
      }
      .orders-link {
         float: right;
      }
      @media screen and (max-width: 540px) {
         .orders-link {
            float: left;
         }
      }
   </style>
@endsection
@section('content')
<main id="orders-summary" class="site-main">
   <div class="container mt-2">
      <div class="row">
         <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="member-wrap-top">
               <h2>Archived Orders</h2>
            </div>
         </div>
         <div class="col-lg-6 col-md-6 col-sm-12">
            <a class="orders-link" href="{{ route('vendor.orders.all') }}">
               <button class="btn">View All Orders</button>
            </a>
         </div>
      </div>
      <hr>
   </div>
</main>
<livewire:vendor-archived-orders :vendor="$vendor" :categories="$categories" />
@endsection
