@if ($isInCart)
    <div wire:click.prevent="addToCart" class="right-header__button btn col-lg-3">
        <a title="Add Service to Cart" href="#">
            <i class="far fa-check-circle"></i>
            <span>Added</span>
        </a>
    </div>
@else
    <div wire:click.prevent="addToCart" class="right-header__button btn col-lg-3">
        <a title="Add Service to Cart" href="#">
            <span>Get Custom Quote</span>
        </a>
    </div>
@endif
