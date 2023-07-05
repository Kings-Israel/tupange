@extends('layouts.master')
@section('title', 'Event Budget')
@section('css')
<style>
   @media only screen and (max-width: 768px) {
      #budgets-container {
         margin-top: 20px;
         transition: .2s ease;
      }
      .budgets {
         transition: .2s ease;
      }
      .add-budget {
         margin-top: -15px !important;
      }
      .budget-to-dashboard {
         margin-top: -10px;
         margin-bottom: 15px;
      }
   }
   @media only screen and (max-width: 575px) {
      .budget-to-dashboard {
         margin-top: -10px;
         margin-bottom: 15px;
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
               <div class="member-wrap-top mt-2">
                  <h2>{{ $event->event_name }}'s Budget and Expenses</h2>
                  <div>
                     <a class="btn budget-to-dashboard" href="{{ route('events.show', $event) }}">Back to My Event</a>
                     @if ($budgets->count() || $initial_budget->count())
                        <button class="btn m-2 add-budget" data-bs-toggle="modal" data-bs-target="#add-budget-{{ $event->id }}" style="background-color: #F58C1C">Add Budget</button>
                     @endif
                     @include('partials.add-budget')
                  </div>
               </div><!-- .member-place-wrap -->
               @if ( ! $budgets->count() && ! $initial_budget->count())
                  <div>
                     <i class="fas fa-money-check-alt" style="font-size: 50px"></i>
                     <p>Create a budget, top up and manage your event transactions</p>
                  </div>
                  <br>
                  <div class="row">
                     <div class="col-md-6">
                        <h3 class="modal-header-title">Add Initial Budget</h3>
                        <div class="login-form">
                           <div class="submit-section">
                              <div class="row">
                                    <div class="form-group col-md-12">
                                       <form action="{{ route('client.event.budget.initial.add') }}" method="POST" enctype="multipart/form-data" >
                                          @csrf
                                          <input type="hidden" name="event_id" value="{{ $event->id }}" id="">
                                          <div class="field-group field-input">
                                                <label for="company_logo">Title</label>
                                                <input type="text" name="title" class="form-control" value="Initial Budget" readonly required>
                                                <x-input-error for="title"></x-input-error>
                                          </div>
                                          <div class="field-group field-input">
                                             <label class="label">Amount *</label>
                                             <input type="number" min="1" name="amount" class="form-control" required>
                                             <x-input-error for="amount"></x-input-error>
                                          </div>
                                          <div class="field-group field-input">
                                                <h4 class="label">Description</h4>
                                                <textarea name="description" class="form-control" ></textarea>
                                                <x-input-error for="description"></x-input-error>
                                             </div>
                                          <br>
                                          <input type="submit" value="Submit" class="btn">
                                       </form>
                                    </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               @else
               <div class="member-statistical">
                  <div class="row">
                     <div class="col-lg-4 col-md-6 col-sm-12" id="pie-chart-container">
                        <div id="pie-chart"></div>
                     </div>
                     <div class="col-lg-8 col-md-6 col-sm-12" id="budgets-container">
                        <div class="row">
                           <div class="col-lg-3 col-md-6 col-sm-12">
                              <div class="item green">
                                 <a href="{{ route('client.event.budget.transactions', ['event' => $event, 'budget' => $initial_budget->budget_id]) }}">
                                    <h3>{{ $initial->title }}</h3>
                                    <h2>Balance: Ksh.<b>{{ number_format($initial_budget->amount) }}</b></h2>
                                    <span>{{ $initial_budget->created_at->diffForHumans() }}</span>
                                    <span class="line"></span>
                                 </a>
                                 {{-- <i class="las la-edit" data-bs-toggle="modal" data-bs-target="#edit-budget-{{ $initial_budget->id }}" style="cursor: pointer; font-size: 20px"></i> --}}
                                 {{-- <i class="la la-trash" data-bs-toggle="modal" data-bs-target="#delete-budget-{{ $budget->id }}" style="cursor: pointer; font-size: 20px"></i> --}}
                              </div>
                           </div>
                           @foreach ($budgets as $budget)
                              @include('partials.edit-budget')
                              @include('partials.delete-budget')
                              <div class="col-lg-3 col-6 budgets">
                                 <div class="item green">
                                    <a href="{{ route('client.event.budget.transactions', ['event' => $event, 'budget' => $budget]) }}">
                                       <h3>{{ $budget->title }}</h3>
                                       <h2>Balance: Ksh.<b>{{ number_format($budget->balance) }}</b></h2>
                                       <span>{{ $budget->created_at->diffForHumans() }}</span>
                                       <span class="line"></span>
                                    </a>
                                    <i class="las la-edit" data-bs-toggle="modal" data-bs-target="#edit-budget-{{ $budget->id }}" style="cursor: pointer; font-size: 20px"></i>
                                    <i class="la la-trash" data-bs-toggle="modal" data-bs-target="#delete-budget-{{ $budget->id }}" style="cursor: pointer; font-size: 20px"></i>
                                 </div>
                              </div>
                           @endforeach
                        </div>
                     </div>
                  </div>
               </div>
               @endif
            </div><!-- .member-wrap-top -->
         </div>
      </div><!-- .site-content -->
   </main><!-- .site-main -->
</div>
   @push('scripts')
   <script>
      let event_number = $('[id=event_number_view]')
      event_number.each((ind, obj) => {
         var num = obj.innerHTML.replace(/,/gi, "");
         var num2 = num.split(/(?=(?:\d{3})+$)/).join(",")
         obj.innerHTML = num2
      });
   </script>
   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
   <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
         var data = google.visualization.arrayToDataTable([
            ['Category Name', 'Amount'],

               @php
                  foreach($chartData as $data) {
                        echo "['".$data['name']."', ".$data['amount']."],";
                  }
               @endphp
         ]);

         var options = {
            title: 'Expenditure Per Category',
            is3D: true,
            legend: 'bottom',
            width: 400,
            height: 300
         };

         var chart = new google.visualization.PieChart(document.getElementById('pie-chart'));

         chart.draw(data, options);
      }

      let chartData = {!! json_encode($chartData) !!}
      if (chartData.length == 0) {
         document.getElementById('pie-chart-container').setAttribute('hidden', 'hidden')
         document.getElementById('budgets-container').classList.remove('col-lg-8')
         document.getElementById('budgets-container').classList.remove('col-md-6')
         document.getElementById('budgets-container').classList.add('col-lg-12')
         document.getElementById('budgets-container').classList.remove('col-md-3')
      }
   </script>
   @endpush
@endsection
