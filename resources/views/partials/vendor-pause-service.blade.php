<div class="modal fade signup" id="pause-service-{{ $service->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
            <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                  <i class="las la-times la-24-black"></i>
            </a><!-- .popup__close -->
         </span>
         <div class="modal-body">
            <h3 class="modal-header-title">Pause Service {{ $service->service_title }}</h3>
            <div class="login-form">
               <div class="submit-section">
                  <div class="row">
                     <div class="form-group col-md-12">
                        <p style="color: red">Are you sure you want to pause this service? It will not show up in the services page for the clients search page.</p>
                        <button class="btn" data-bs-dismiss="modal" class="popup__close" wire:click="pauseService({{ $service }})">Pause</button>
                        <span data-bs-dismiss="modal" class="popup__close">Cancel</span>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
