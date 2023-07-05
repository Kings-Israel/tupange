<div class="modal fade signup" id="add-budget-{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
       <div class="modal-content" id="sign-up">
           <span>
               <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                   <i class="las la-times la-24-black"></i>
               </a><!-- .popup__close -->
           </span>
           <div class="modal-body">
               <h3 class="modal-header-title">Add Budget</h3>
               <div class="login-form">
                   <div class="submit-section">
                       <div class="row">
                           <div class="form-group col-md-12">
                               <form action="{{ route('client.event.budget.add') }}" method="POST" enctype="multipart/form-data" >
                                   @csrf
                                   <input type="hidden" name="event_id" value="{{ $event->id }}" id="">
                                   <div class="field-group field-input">
                                       <label for="company_logo">Title</label>
                                       <input type="text" name="title" class="form-control" required>
                                       <x-input-error for="title"></x-input-error>
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
   </div>
</div>

<!-- End Modal -->
