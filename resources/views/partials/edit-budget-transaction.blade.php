<div class="modal fade signup" id="edit-transaction-{{ $transaction->id }}" tabindex="-1" role="dialog" aria-labelledby="edit-transaction-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
       <div class="modal-content" id="sign-up">
           <span>
               <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                   <i class="las la-times la-24-black"></i>
               </a><!-- .popup__close -->
           </span>
           <div class="modal-body">
               <h3 class="modal-header-title">Edit Transaction</h3>
               <div class="login-form">
                   <div class="submit-section">
                       <div class="row">
                           <div class="form-group col-md-12">
                              <form action="{{ route('client.event.budget.transaction.edit') }}" method="POST" enctype="multipart/form-data" >
                                 @csrf
                                 <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                                 <div class="row">
                                    <div class="col-lg-6">
                                       <div class="field-group field-input">
                                          <h4 class="label">Transaction Date *</h4>
                                          <input type="date" name="date" class="form-control" value="{{ $transaction->date }}" required>
                                          <x-input-error for="date"></x-input-error>
                                       </div>
                                    </div>
                                    <div class="col-lg-6">
                                       <div class="field-group field-input">
                                          <h4 class="label">Transaction Type *</h4>
                                          <select name="type" id="" class="form-control">
                                             <option value="Expense" {{ $transaction->type == 'Expense' ? 'selected' : '' }}>Expense</option>
                                             <option value="Top Up" {{ $transaction->type == 'Top Up' ? 'selected' : '' }}>Top Up</option>
                                          </select>
                                          <x-input-error for="type"></x-input-error>
                                       </div>
                                    </div>
                                 </div>

                                 <div class="field-group field-input">
                                    <label class="label">Title *</label>
                                    <input type="text" name="title" class="form-control" value="{{ $transaction->title }}" required>
                                    <x-input-error for="title"></x-input-error>
                                 </div>

                                 <div class="field-group field-input">
                                    <label class="label">Description</label>
                                    <textarea name="description" class="form-control">{{ $transaction->description }}</textarea>
                                    <x-input-error for="description"></x-input-error>
                                 </div>

                                 <div class="field-group field-input">
                                    <label class="label">Amount *</label>
                                    <input type="number" min="1" name="amount" class="form-control" value="{{ $transaction->amount }}" required>
                                    <x-input-error for="amount"></x-input-error>
                                 </div>

                                 <div class="field-group field-input">
                                    <label class="label">Reference</label>
                                    <input type="text" name="reference" class="form-control" value="{{ $transaction->reference }}">
                                    <x-input-error for="reference"></x-input-error>
                                 </div>
                                 <div class="field-group field-input">
                                    <h4 class="label">Category</h4>
                                    <select name="transaction_service_category" id="" class="form-control">
                                       <option disabled>Select the service category that the transaction was made for</option>
                                       <option value="">None</option>
                                       @foreach ($categories as $category)
                                          <option value="{{ $category }}" {{ $category == $transaction->transaction_service_category ? 'selected' : '' }}>{{ $category }}</option>
                                       @endforeach
                                    </select>
                                    <x-input-error for="transaction_service_category"></x-input-error>
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
   </div>
</div>

<!-- End Modal -->
