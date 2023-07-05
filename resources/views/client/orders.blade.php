@extends('layouts.master')
@section('title', 'Orders')
@section('css')
   <style>
      .search-results {
         width: 200px !important;
         margin-top: -12px !important;
         margin-left: 14px !important;
      }
      /* .result-link {
         padding: 1px !important;
      } */
      .result-link .result {
         margin: 8px 0 8px 0 !important;
         padding: 0 5px;
      }
      .result-link {
         color: #fff;
      }
      .result-link:hover {
         background: rgb(208, 208, 208) !important;
         color: rgb(52, 52, 52) !important;
      }
      .client-checkout-btn {
         width: 40%;
      }
      .mf-left {
         display: flex;
      }
      @media only screen and (max-width: 768px) {
         .client-checkout-btn {
            width: 100% !important;
         }
         .mf-left {
            flex-wrap: wrap;
         }
         .mf-left .field-input {
            margin-bottom: 20px;
            width: 100%;
         }
         .mf-left .field-input input {
            width: 100%;
         }
      }
      @media only screen and (max-width: 576px) {
         .client-checkout-btn div:nth-child(2) {
            width: 50%;
         }
      }
   </style>
@endsection
@section('content')
   <div class="container">
      <div class="member-place-wrap">
         <div class="member-wrap-top">
            <h2>Orders History</h2>
         </div>
         <livewire:client-orders-view />
      </div>
   </div>

@endsection
