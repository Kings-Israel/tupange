<div>
   <a href="#" class="golo-add-to-wishlist btn-add-to-wishlist">
      <span class="icon-heart">
         @if($isInFavorites)
            <i wire:click.prevent="addToFavorites" class="fas fa-heart" style="color: red; margin-top: 3px; font-size: 20px"></i>
         @else
            <i wire:click.prevent="addToFavorites" class="far fa-heart" style="margin-top: 3px; font-size: 20px"></i>
         @endif
      </span>
   </a>
</div>
