<div class="modal fade signup" id="share-program-{{ $eventProgram->id }}" tabindex="-1" role="dialog" aria-labelledby="edit-budget-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
       <div class="modal-content" id="sign-up">
           <span>
               <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                   <i class="las la-times la-24-black"></i>
               </a><!-- .popup__close -->
           </span>
           <div class="modal-body">
               <h3 class="modal-header-title">Enter The Emails You would like to share to</h3>
               <div class="login-form">
                   <div class="submit-section">
                       <div class="row">
                           <div class="form-group col-md-12">
                              <form action="{{ route('client.program.share') }}" method="POST" enctype="multipart/form-data" >
                                 @csrf
                                 <input type="hidden" name="program_id" value="{{ $eventProgram->id }}">
                                 <div class="field-group field-input">
                                    <label class="label">Enter the emails here, separated by a comma (",")</label>
                                    <textarea name="emails" class="form-control" placeholder="E.g: johndoe@gmail.com, maryjane@outlook.com" required></textarea>
                                    <x-input-error for="emails"></x-input-error>
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
