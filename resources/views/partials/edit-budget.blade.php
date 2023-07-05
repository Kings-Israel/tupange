<div class="modal fade signup" id="edit-budget-{{ $budget->id }}" tabindex="-1" role="dialog" aria-labelledby="edit-budget-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
       <div class="modal-content" id="sign-up">
           <span>
               <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                   <i class="las la-times la-24-black"></i>
               </a><!-- .popup__close -->
           </span>
           <div class="modal-body">
               <h3 class="modal-header-title">Edit Budget</h3>
               <div class="login-form">
                   <div class="submit-section">
                       <div class="row">
                           <div class="form-group col-md-12">
                              <form action="{{ route('client.event.budget.edit') }}" method="POST" enctype="multipart/form-data" >
                                 @csrf
                                 <input type="hidden" name="budget_id" value="{{ $budget->id }}">

                                 <div class="field-group field-input">
                                    <label class="label">Title *</label>
                                    <input type="text" name="title" class="form-control" value="{{ $budget->title }}" required>
                                    <x-input-error for="title"></x-input-error>
                                 </div>

                                 <div class="field-group field-input">
                                    <label class="label">Description</label>
                                    <textarea name="description" class="form-control">{{ $budget->description }}</textarea>
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
   </div>
</div>

<!-- End Modal -->
