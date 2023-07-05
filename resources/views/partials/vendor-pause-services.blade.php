<div class="modal fade signup" id="pause-services" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
            <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                  <i class="las la-times la-24-black"></i>
            </a><!-- .popup__close -->
         </span>
         <div class="modal-body">
            <h3 class="modal-header-title">Pause All Services</h3>
            <div class="login-form">
               <div class="submit-section">
                  <div class="row">
                     <div class="form-group col-md-12">
                        <p style="color: red">Are you sure you want to pause your services? They will not show up in the services page for the clients' search page.</p>
                        <br>
                        <div class="d-flex justify-content-between">
                           <span data-bs-dismiss="modal" class="popup__close btn" style="cursor: pointer; background-color:red">Cancel</span>
                           <a href="{{ route('vendor.services.pause') }}">
                              <button class="btn" style="background-color: #1DA1F2">Pause</button>
                           </a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- End Modal -->
