<div class="modal fade signup" id="add-user-{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
       <div class="modal-content" id="sign-up">
           <span>
               <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                   <i class="las la-times la-24-black"></i>
               </a><!-- .popup__close -->
           </span>
           <div class="modal-body">
               <h3 class="modal-header-title">Add User</h3>
               <div class="login-form">
                  <div class="submit-section">
                     <div class="row">
                        <div class="form-group col-md-12">
                           <form action="{{ route('client.event.user.add') }}" method="POST" enctype="multipart/form-data" >
                              @csrf
                              <input type="hidden" name="event_id" value="{{ $event->id }}" id="">
                              <div class="row">
                                 <div class="col-lg-6">
                                    <div class="field-group field-input">
                                       <label for="user_name">User Names</label>
                                       <input type="text" name="names" class="form-control" required>
                                       <x-input-error for="names"></x-input-error>
                                    </div>
                                 </div>
                                 <div class="col-lg-6">
                                    <div class="field-group field-input">
                                       <label for="user_name">Email</label>
                                       <input type="email" name="email" class="form-control" required>
                                       <x-input-error for="names"></x-input-error>
                                    </div>
                                 </div>
                              </div>
                              <div class="field-group field-input">
                                 <label class="label">Role</label>
                                 <select name="role" id="" class="form-control">
                                    <option value="Committee">Committee</option>
                                    <option value="Tasks">Tasks</option>
                                 </select>
                                 <x-input-error for="role"></x-input-error>
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
