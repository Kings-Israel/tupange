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
         .owner-content {
            margin-top: -150px;
         }
         .orders-link {
            float: left;
         }
      }
   </style>
@endsection
@section('content')
<main id="orders-summary" class="site-main">
   <div class="container">
      <h1>Orders Summary</h1>
      <div class="member-statistical">
         <div class="row">
            @foreach ($ordersSummary as $key => $summary)
               <div class="col-sm-6 col-md-4 col-lg-3">
                  <div class="item green">
                     <h3>{{ $summary['status'] }} - <span>{{ $summary['number'] }}</span></h3>
                     <span class="number" style="display: flex; justify-content: center">Ksh. <p id="order_money_view">{{ $summary['total_amount'] }}</p></span>
                     <span class="line"></span>
                  </div>
               </div>
            @endforeach
         </div>
      </div>
   </div>
</main>
<livewire:vendor-orders-view :vendor="$vendor" :categories="$categories" />
@push('scripts')
    <script>
      let event_number = $('[id=order_money_view]')
      event_number.each((ind, obj) => {
         var num = obj.innerHTML.replace(/,/gi, "");
         var num2 = num.split(/(?=(?:\d{3})+$)/).join(",")
         obj.innerHTML = num2
      });
    </script>
@endpush
@endsection
