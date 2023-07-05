<div class="place-item layout-02 place-hover">
    <div class="place-inner">
        <div class="place-thumb">
            <a class="entry-thumb" href="{{ route('client.service.one', $service) }}"><img class="service-cover-image" src="{{ $service->getCoverImage($service->service_image) }}" alt="{{ $service->service_title }}" onerror="this.onerror=null; this.src='{{ $service->vendor->getCompanyLogo($service->vendor->company_logo) }}'"></a>
            <div class="service_gallery_images_preview">
               @if (count($service->service_images))
                  <div class="service_images_preview">
                     @foreach ($service->service_images->shuffle()->take(3) as $image)
                        <img class="service_image_preview" src="{{ $service->getImage($image->image) }}" alt="{{ $service->service_title }}" onerror="this.onerror=null; this.src='{{ $service->vendor->getCompanyLogo($service->vendor->company_logo) }}'">
                     @endforeach
                  </div>
               @endif
            </div>
            @auth
                @if (auth()->user()->id != $service->vendor_id)
                    <a href="#" class="golo-add-to-wishlist btn-add-to-wishlist " data-place-id="185">
                        <span class="icon-heart">
                            @if($isInFavorites)
                                <i wire:click.prevent="addToFavorites" class="fas fa-heart" style="color: red; margin-top: 3px"></i>
                            @else
                                <i wire:click.prevent="addToFavorites" class="far fa-heart" style="margin-top: 3px"></i>
                            @endif
                        </span>
                    </a>
                @endif
            @endauth
        </div>
        <div class="entry-detail">
            <div class="entry-head">
                <div class="place-type list-item">
                    <span>{{ $service->getCategory($service->category_id)->name }}</span>
                </div>
                <div class="place-city">
                  {{ $service->vendor->company_name }}
                </div>
            </div>
            <h3 class="place-title"><a href="{{ route('client.service.one', $service) }}">{{ $service->service_title }}</a></h3>
            <div class="address" style="margin-top: -20px">
                <i class="la la-map-marker"></i>
                <span id="location">{{ $service->service_location }}</span>
            </div>
            <div class="entry-bottom">
                <div class="place-preview">
                    <div class="place-rating">
                        <span>{{ $service->service_rating }}</span>
                        <i class="la la-star"></i>
                    </div>
                    <span class="count-reviews">({{ $service->reviews->count() }} Reviews)</span>
                </div>

                @auth
                  @if (auth()->user()->id != $service->vendor->user_id)
                     <div class="place-price" style="cursor: pointer">
                        <a title="View Pricing Packages for the service" href="{{ route('client.service.one', $service) }}">View Packages</a>
                     </div>
                  @endif
               @else
                  <a title="View Pricing Packages for the service" href="{{ route('client.service.one', $service) }}">View Packages</a>
               @endauth
                {{-- @auth
                    @if (auth()->user()->id != $service->vendor_id)
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
                    @endif
                @endauth --}}
            </div>
        </div>
    </div>
</div>
