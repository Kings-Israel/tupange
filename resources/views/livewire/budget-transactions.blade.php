<main id="main" class="site-main">
   <div class="site-content owner-content budget-transactions">
      <div class="container">
         <div class="member-place-wrap">
            <div class="member-wrap-top">
               <h2>{{ $budget->title }} Budget Transactions</h2>
               <div>
                  <a class="btn btn-info mt-1" style="background-color: #1da1f2" href="{{ route('client.event.budget', $event) }}">Back to {{ $event->event_name }} Budget Dashboard</a>
               </div>
            </div><!-- .member-place-wrap -->
            <div class="member-statistical">
               <div class="row">
                   <div class="col-lg-4 col-6">
                        <div class="item green">
                              <h3>Budget</h3>
                              <span class="number" style="display: flex; justify-content: center">Ksh.<p>{{ number_format($budgetTransactions->budget) }}</p></span>
                              <span class="line"></span>
                        </div>
                   </div>
                   <div class="col-lg-4 col-6">
                        <div class="item yellow">
                              <h3>Spent</h3>
                              <span class="number" style="display: flex; justify-content: center">Ksh.<p>{{ number_format($budgetTransactions->spent) }}</span>
                              <span class="line"></span>
                        </div>
                   </div>
                   <div class="col-lg-4 col-6">
                        <div class="item magenta">
                           <h3>Balance</h3>
                           @if ($budgetTransactions->balance < 0)
                              <span class="number" style="color: red; display: flex; justify-content: center">Ksh. <p>{{ number_format($budgetTransactions->balance) }}</p></span>
                           @else
                              <span class="number" style="display: flex; justify-content: center">Ksh.<p>{{ number_format($budgetTransactions->balance) }}</p></span>
                           @endif
                              <span class="line"></span>
                        </div>
                   </div>
               </div>
            </div>
            <div class="filter-group">
               <div class="">
                  <form action="#" method="GET">
                     <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12">
                           <div class="field-select field-group form-group">
                              <label for="title" class="label">Title</label>
                              <input type="text" name="title" wire:model="title" class="form-control" id="" placeholder="Search Title">
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                           <div class="field-select form-group">
                                 <select name="place_cities" wire:model="type" class="form-control">
                                    <option value="All">Select Transaction Types</option>
                                    <option value="Expense">Expense</option>
                                    <option value="Top Up">Top Up</option>
                                 </select>
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                           <div class="field-select form-group">
                              <select name="place_cities" class="form-control" wire:model="transaction_service_category">
                                 <option value="All">Select Category</option>
                                 @foreach ($categories as $category)
                                    <option value="{{ $category }}">{{ $category }}</option>
                                 @endforeach
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12">
                           {{-- <button class="btn" style="float: right" data-bs-toggle="modal" data-bs-target="#add-transaction-{{ $budget->id }}">Add Expense</button> --}}
                           <a class="btn" href="{{ route('client.event.budget.transaction.form.show', ['event' => $event, 'budget' => $budget]) }}" style="float: right">Add Expense</a>
                        </div>
                     </div>
                  </form>
               </div><!-- .mf-left -->
            </div><!-- .member-filter -->
            @if ($budgetTransactions->count())
               <table class="member-place-list owner-booking table-responsive">
                  <thead>
                     <tr>
                        <th class="table-width-150">Transaction Date</th>
                        <th class="table-width-250">Title</th>
                        <th class="table-width-100">Type</th>
                        <th class="table-width-150">Amount</th>
                        <th class="table-width-100">Reference</th>
                        <th class="table-width-100">Category</th>
                        <th>Recorded On</th>
                        <th class="table-width-50">Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach ($budgetTransactions as $transaction)
                        @include('partials.edit-budget-transaction')
                        @include('partials.delete-transaction')
                        <tr>
                           <td class="table-width-150" data-title="Date">
                              {{ Carbon\Carbon::parse($transaction->date)->format('d M, Y') }}
                           </td>
                           <td class="table-width-250" data-title="Title">
                              @can('view', $transaction)
                                 <a href="{{ route('client.event.order.view', $transaction->title) }}">
                                    {{ $transaction->title }}
                                 </a>
                              @else
                                 {{ $transaction->title }}
                              @endcan
                           </td>
                           <td data-title="Type" class="table-width-100">{{ $transaction->type }}</td>
                           <td data-title="Amount" class="table-width-150">Ksh. <p>{{ number_format($transaction->amount) }}</p></td>
                           <td data-title="Reference" class="table-width-100">{{ $transaction->reference }}</td>
                           <td data-title="Category" class="table-width-100">{{ $transaction->transaction_service_category }}</td>
                           <td data-title="Recorded On">{{ Carbon\Carbon::parse($transaction->created_at)->format('d M, Y') }}</td>
                           <td style="display: flex">
                              @can('update', $transaction)
                                 <i class="las la-edit" data-bs-toggle="modal" data-bs-target="#edit-transaction-{{ $transaction->id }}" style="cursor: pointer; font-size: 20px;"></i>
                                 <i class="la la-trash" data-bs-toggle="modal" data-bs-target="#delete-transaction-{{ $transaction->id }}" style="cursor: pointer; font-size: 20px;"></i>
                              @endcan
                           </td>
                        </tr>
                     @endforeach
                  </tbody>
               </table>
               <div class="d-flex justify-content-between">
                  {{ $budgetTransactions->links() }}
                  <a class="btn" href="{{ route('client.event.budget.transaction.form.show', ['event' => $event, 'budget' => $budget]) }}">Add Expense</a>
               </div>
            @else
               No Transaction found
            @endif
         </div><!-- .member-wrap-top -->
      </div>
   </div><!-- .site-content -->
   @push('scripts')
       <script>
         let event_number = $('[id=event_number_view]')
         event_number.each((ind, obj) => {
            var num = obj.innerHTML.replace(/,/gi, "");
            var num2 = num.split(/(?=(?:\d{3})+$)/).join(",")
            obj.innerHTML = num2
         });
       </script>
   @endpush
</main><!-- .site-main -->
