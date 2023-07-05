<div class="modal fade signup" id="add-ticket-{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-lg modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content" id="sign-up">
         <span>
            <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close m-2" style="float: right; font-size: 20px">
                  <i class="las la-times la-24-black"></i>
            </a><!-- .popup__close -->
         </span>
         <div class="modal-body">
            <h3 class="modal-header-title">Add Ticket</h3>
            <div class="login-form">
                  <div class="submit-section">
                     <div class="row">
                        <div class="form-group col-md-12">
                              <form action="{{ route('client.event.ticket.add') }}" method="POST" enctype="multipart/form-data" >
                                 @csrf
                                 <input type="hidden" name="event_id" value="{{ $event->id }}" id="">
                                 <div class="row">
                                    <div class="col-lg-4">
                                       <div class="field-group field-input">
                                          <label>Ticket Title *</label>
                                          <input type="text" name="title" id="" class="form-control" placeholder="Ticket Title" required>
                                          <x-input-error for="title"></x-input-error>
                                       </div>
                                    </div>
                                    <div class="col-lg-4">
                                       <div class="field-group field-input">
                                          <label>Ticket Price *</label>
                                          <input type="number" min="1" name="price" class="form-control" value="1" id="guest_number" required>
                                          <x-input-error for="price"></x-input-error>
                                       </div>
                                    </div>
                                    <div class="col-lg-4">
                                          <div class="field-group field-input">
                                             <label>Guest Limit</label>
                                             <input type="number" name="guest_limit" min="1" id="" placeholder="Guest Limit">
                                             <x-input-error for="guest_limit"></x-input-error>
                                          </div>
                                    </div>
                                 </div>
                                 <div class="field-group field-input">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control" placeholder="Ticket Type Description"></textarea>
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
