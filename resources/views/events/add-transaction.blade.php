@extends('layouts.master')
@section('title', 'Add Transaction')
@section('css')
<link href="{{ asset('assets/css/select-searchbox/common.css') }}" rel="stylesheet" />
<style>
   .site-content {
      background-color: #F8F8F8;
   }
   .filter-group {
      background-color: #fff;
      width: 50%;
      margin-left: 25%;
      padding: 15px;
      border-radius: 10px;
      border: 1px solid #eeeeee;
   }
   .searchBoxElement {
      max-height:300px;
      z-index: 2;
   }
   .nav-search-btn {
      margin: 2px 2px 0 0;
   }
   .transaction_service_category {
      max-width: 100%;
   }
   @media only screen and (max-width: 768px) {
      .filter-group {
         width: 100%;
         margin-left: 0;
         margin-top: 20px;
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
               <h2>Add {{ $budget->title }} Transaction</h2>
               <div>
                  <a class="btn btn-info mt-1" style="background-color: #1da1f2; color: white; text-decoration: none;" href="{{ route('client.event.budget.transactions', ['event' => $event, 'budget' => $budget]) }}">Back to {{ $event->event_name }} Budget Transactions</a>
               </div>
            </div><!-- .member-place-wrap -->
            <div class="filter-group">
               <div class="login-form">
                   <div class="submit-section">
                       <div class="row">
                           <div class="form-group col-md-12">
                              <form action="{{ route('client.event.budget.transaction.add') }}" method="POST" enctype="multipart/form-data" >
                                 @csrf
                                 <input type="hidden" name="event_id" value="{{ $event->id }}">
                                 <input type="hidden" name="budget_id" value="{{ $budget->id }}">
                                 <div class="row">
                                    <div class="col-lg-6">
                                       <div class="field-group field-input">
                                          <h4 class="label">Transaction Date *</h4>
                                          <input type="date" name="date" class="form-control" required>
                                          <x-input-error for="date"></x-input-error>
                                       </div>
                                    </div>
                                    <div class="col-lg-6">
                                       <div class="field-group field-input">
                                          <h4 class="label">Transaction Type *</h4>
                                          <select name="type" class="form-control">
                                             <option value="Expense">Expense</option>
                                             <option value="Top Up">Top Up</option>
                                          </select>
                                          <x-input-error for="type"></x-input-error>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="field-group field-input mt-1">
                                    <label class="label">Title *</label>
                                    <input type="text" name="title" class="form-control" required>
                                    <x-input-error for="title"></x-input-error>
                                 </div>

                                 <div class="field-group field-input mt-1">
                                    <label class="label">Description</label>
                                    <textarea name="description" class="form-control"></textarea>
                                    <x-input-error for="description"></x-input-error>
                                 </div>

                                 <div class="field-group field-input mt-1">
                                    <label class="label">Amount *</label>
                                    <input type="phone_number" min="1" name="amount" class="form-control" required autocomplete="off">
                                    <x-input-error for="amount"></x-input-error>
                                 </div>

                                 <div class="field-group field-input mt-1">
                                    <label class="label">Reference</label>
                                    <input type="text" name="reference" class="form-control" autocomplete="off">
                                    <x-input-error for="reference"></x-input-error>
                                 </div>
                                 <div class="field-group field-input mt-1">
                                    <label class="label">Service Category</label>
                                    <select name="transaction_service_category" class="form-control js-searchBox">
                                       <option disabled selected>Select the service category that the transaction was made for</option>
                                       <option value="">None</option>
                                       @foreach ($categories as $category)
                                          <option data-tokens="{{ $category }}" value="{{ $category }}">{{ $category }}</option>
                                       @endforeach
                                    </select>
                                    <x-input-error for="transction_service_category"></x-input-error>
                                 </div>
                                 <br>
                                 <input type="submit" value="Submit" class="btn" style="float: right">
                              </form>
                           </div>
                       </div>
                   </div>
               </div>
            </div><!-- .member-filter -->
         </div><!-- .member-wrap-top -->
      </div>
   </div><!-- .site-content -->
</main><!-- .site-main -->
@push('scripts')
<script src="{{ asset('assets/js/select-searchbox/jquery-searchbox.js') }}"></script>
<script>
   $('.js-searchBox').searchBox();
</script>
@endpush
@endsection
