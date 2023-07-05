<div class="modal fade signup" id="dispute" tabindex="-1" role="dialog" aria-labelledby="edit-budget-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
       <div class="modal-content" id="sign-up">
           <span>
               <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                   <i class="las la-times la-24-black"></i>
               </a><!-- .popup__close -->
           </span>
           <div class="modal-body">
               <h3 class="modal-header-title">Enter Dispute Reason</h3>
               <div class="login-form">
                   <div class="submit-section">
                       <div class="row">
                           <div class="form-group col-md-12">
                              <form action="{{ route('dispute') }}" method="POST" id="feedback" enctype="multipart/form-data" >
                                 @csrf
                                 <div class="field-group field-input">
                                    <label for="">Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Please enter your name" required>
                                 </div>
                                 <div class="field-group field-input">
                                    <label for="">Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Please enter email name" required>
                                 </div>
                                 <div class="field-group field-input">
                                    <label class="label">Please explain your comments here</label>
                                    <textarea name="comments" class="form-control" required></textarea>
                                    <x-input-error for="description"></x-input-error>
                                 </div>
                                 <br>
                                 <input type="submit" value="Submit" id="submit-feedback" class="btn">
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
