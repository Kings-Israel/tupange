<div>
    <div class="place-price" style="cursor: pointer">
        @if ($isInCart)
            <span wire:click.prevent="addToCart">
                <i class="fas fa-shopping-cart" style="color: #8FCA27"></i>
                In Cart
            </span>
        @else
            <span wire:click.prevent="addToCart">
                <i class="fas fa-shopping-cart"></i>
                Add
            </span>
        @endif
    </div>
</div>
