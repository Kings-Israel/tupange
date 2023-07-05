<div class="modal fade signup" id="delete-service-{{ $service->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
            <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close" style="float: right">
                  <i class="las la-times la-24-black"></i>
            </a><!-- .popup__close -->
         </span>
         <div class="modal-body">
            <h3 class="modal-header-title">Delete Service {{ $service->service_title }}</h3>
            <div class="login-form">
               <div class="submit-section">
                  <div class="row">
                     <div class="form-group col-md-12">
                        <p style="color: red">Are you sure you want to delete this service? <br /> It will not show up in the services page for the clients search page.</p>
                        <div class="d-flex justify-content-between mt-2">
                           <div>
                              <form action="{{ route('vendor.service.pause') }}" method="post" id="pause-service">
                                 @csrf
                                 <input type="hidden" name="service_id" value="{{ $service->id }}">
                                 <input type="hidden" name="action" id="action" value='Pause Indefinitely'>
                                 <button class="btn" style="background-color: #F58C1C">Pause Service</button>
                              </form>
                              <button class="btn" data-bs-dismiss="modal" class="popup__close" wire:click="deleteService({{ $service }})">Delete Service</button>
                           </div>
                           <span data-bs-dismiss="modal" class="popup__close btn" style="background-color: red">Cancel</span>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
