<div class="modal fade signup" id="delete-event-role-{{ $role->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
       <div class="modal-content" id="sign-up">
           <span>
               <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close m-2" style="float: right; font-size: 20px">
                   <i class="las la-times la-24-black"></i>
               </a><!-- .popup__close -->
           </span>
           <div class="modal-body">
               <h3 class="modal-header-title" style="color: red">Delete Role</h3>
               <div class="login-form">
                   <div class="submit-section">
                       <div class="row">
                           <div class="form-group col-md-12">
                               <form action="{{ route('client.events.role.delete') }}" method="POST" enctype="multipart/form-data" >
                                   @csrf
                                   <input type="hidden" name="role_id" value="{{ $role->id }}">
                                   <p style="color: red">
                                       Are you sure you want to delete your role from the event <strong>{{ $role->event->event_name }}</strong>.
                                       This action cannot be undone.
                                   </p>
                                   <br>
                                   <input type="submit" value="Delete Role" class="btn btn-danger" style="background: red">
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
