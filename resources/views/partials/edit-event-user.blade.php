<div class="modal fade signup" id="edit-event-user-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
            <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close m-2" style="float: right; font-size: 20px">
                  <i class="las la-times la-24-black"></i>
            </a><!-- .popup__close -->
         </span>
         <div class="modal-body">
            <h3 class="modal-header-title">Edit User</h3>
            <div class="login-form">
               <div class="submit-section">
                  <div class="row">
                     <div class="form-group col-md-12">
                        <form action="{{ route('client.event.user.edit') }}" method="POST" enctype="multipart/form-data" >
                           @csrf
                           <input type="hidden" name="user_id" value="{{ $user->id }}" id="">
                           <div class="row">
                              <div class="col-lg-6">
                                 <div class="field-group field-input">
                                    <label for="user_name">User Names</label>
                                    <input type="text" name="names" class="form-control" value="{{ $user->names }}" required>
                                    <x-input-error for="names"></x-input-error>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <div class="field-group field-input">
                                    <label for="user_name">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                    <x-input-error for="names"></x-input-error>
                                 </div>
                              </div>
                           </div>
                           <div class="field-group field-input">
                              <label class="label">Role</label>
                              <select name="role" id="" class="form-control">
                                 <option value="Committee" {{ $user->role == 'Committee' ? 'selected' : '' }}>Committee</option>
                                 <option value="Tasks" {{ $user->role == 'Tasks' ? 'selected' : '' }}>Tasks</option>
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
