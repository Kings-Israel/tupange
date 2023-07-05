<div class="modal fade signup" id="delete-service-{{ $service->id }}" tabindex="-1" role="dialog" aria-hidden="true">
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
                  <form action="{{ route('vendor.service.delete.permanent') }}" method="post" id="pause-service">
                     @csrf
                     <input type="hidden" name="service_id" value="{{ $service->id }}">
                     <p style="color: red">Are you sure you want to delete this service?</p>
                     <br>
                     <div class="d-flex justify-content-between">
                        <span data-bs-dismiss="modal" class="popup__close btn" style="background-color: #F58C1C">Cancel</span>
                        <input type="submit" class="btn" style="background: red" id="delete" value="Delete"/>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- End Modal -->
