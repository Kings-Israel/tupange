<div class="modal fade signup" id="view-image-{{ $image->id }}" tabindex="-1" role="dialog" aria-labelledby="service-images-modal" aria-hidden="true">
   <span>
      <a title="Close" href="#" data-bs-dismiss="modal" class="popup__close service-image-close">
         <i class="las la-times la-24-black" style="font-size: 32px"></i>
      </a><!-- .popup__close -->
   </span>
   <div class="modal-dialog modal-dialog-centered login-pop-form" role="document">
      <div class="modal-content">
         <img class="service-image" src={{ $service->getImage($image->image) }} alt="">
         <br>
         @if ($image->image_description)
            <p class="service-image-description">{{ $image->image_description }}</p>
         @endif
         @auth
            @if (Auth::user()->status == 'vendor' && $service->vendor->user->id == Auth::user()->id)
               <form action="{{ route('vendor.service.image.delete') }}" method="POST">
                  @csrf
                  <input type="hidden" name="image" value="{{ $image->image }}">
                  <div class="modal-footer">
                     <button class="btn btn-danger" type="submit">Delete</button>
                  </div>
               </form>
            @endif
         @endauth
      </div>
   </div>
</div>
<!-- End Modal -->
