<div class="modal fade signup" id="delete-ticket-{{ $ticket->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
       <div class="modal-content" id="sign-up">
           <span>
               <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close m-2" style="float: right; font-size: 20px">
                   <i class="las la-times la-24-black"></i>
               </a><!-- .popup__close -->
           </span>
           <div class="modal-body">
               <h3 class="modal-header-title" style="color: red">Delete Ticket, {{ $ticket->title }}</h3>
               <div class="login-form">
                   <div class="submit-section">
                       <div class="row">
                           <div class="form-group col-md-12">
                               <form action="#" method="POST" enctype="multipart/form-data" >
                                   @csrf
                                   <p style="color: red">
                                       Are you sure you want to delete this ticket?
                                       This action cannot be undone.
                                   </p>
                                   <br>
                                   <a href="{{ route('client.event.ticket.delete', $ticket->id) }}" class="btn btn-danger" style="background: red">Delete Ticket</a>
                                   {{-- <input type="submit" value="Delete Ticket" class="btn btn-danger"> --}}
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
