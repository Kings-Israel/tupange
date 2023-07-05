<div class="row">
   @forelse ($service->service_pricing as $pricing)
      <div class="col-lg-4 col-md-6">
         <div class="place-item layout-02 place-hover mt-3">
            <div class="place-inner">
               <div class="entry-detail pricing-card" style="background-color: #cccccc">
                  <div class="place-type list-item">
                     <span style="display: flex">Ksh. <p>{{ number_format($pricing->service_pricing_price) }}</p></span>
                  </div>
                  <h3 class="place-title" style="margin-bottom: -5px">{{ $pricing->service_pricing_title }}</h3>
                  <div class="package-description">
                     <p>{{ $pricing->service_pricing_description }}</p>
                  </div>
                  <hr>
                  {{-- @if (count($pricing->service_packages))
                        <h6>Packages</h6>
                        <div class="packages-div">
                           @foreach ($pricing->service_packages as $service_package)
                                 <div>{{ $service_package }}</div>
                                 <hr>
                           @endforeach
                        </div>
                  @endif --}}
                  <div class="entry-bottom">
                        <div class="place-price pricing-actions" style="cursor: pointer">
                           @auth
                              @if (auth()->user()->isVendor() && auth()->user()->status == 'vendor' && auth()->user()->vendor->id == $service->vendor_id)
                                 @if ($pricing->hasActiveOrders())
                                    <span class="badge" style="background-color: #737373">{{ $pricing->activeOrdersCount() }} Order(s)</span>
                                 @else
                                    <span class="badge" wire:click="deletePricing({{ $pricing->id }})" style="background-color: #F58C1C"><i class="fas fa-trash"></i> Del</span>
                                 @endif
                                 <span class="badge" id="edit-package" data-bs-toggle="modal" data-bs-target="#pricing-package-update-{{ $pricing->id }}"><i class="fas fa-pencil"></i> Edit</span>
                              @elseif (auth()->user()->status == 'user' && $service->vendor->user_id != auth()->user()->id)
                                 @if ($service->pricingIsInCart(auth()->user(), $pricing))
                                    <span class="badge" wire:click.prevent="addToCart({{ $pricing }})" title="Click to remove from cart">
                                       <i class="fas fa-check"></i>
                                       In Cart
                                    </span>
                                 @else
                                    @if (!$isInCart)
                                       <span class="badge" style="background-color: #F58C1C" wire:click.prevent="addToCart({{ $pricing }})">
                                          <i class="fas fa-shopping-cart"></i>
                                          Add to Cart
                                       </span>
                                    @endif
                                 @endif
                              @endif
                           @else
                              <span class="badge" style="background-color: #F58C1C;">
                                 <a title="Add to Cart" href="{{ url('client/add-to-cart/'.$service->id.'/'.$pricing->id.'') }}" style="color: #fff">
                                    <i class="fa fa-shopping-cart" style="font-size: 14px; cursor: pointer;"></i>
                                    Add To Cart
                                 </a>
                              </span>
                           @endauth
                        </div>
                  </div>
               </div>
            </div>
         </div>
         @auth
            @if(auth()->user()->isVendor() && auth()->user()->status == 'vendor' && auth()->user()->vendor->id == $service->vendor_id)
               @include('partials.edit-service-package')
            @endif
         @endauth
      </div>
   @empty
      <div class="pricing-details">
         <p>No Pricing Package Added</p>
         @guest
            <p>Please <span class="open-login" href="{{ route('login') }}" style="color: #1DA1F2; cursor: pointer;">Login</span> to request a custom quote</p>
         @endguest
      </div>
   @endforelse

   @push('scripts')
      <script>
         let service_pricings = $('[id=service_pricing_view]')
         service_pricings.each((ind, obj) => {
            var num = obj.innerHTML.replace(/,/gi, "");
            var num2 = num.split(/(?=(?:\d{3})+$)/).join(",")
            obj.innerHTML = num2
         });
      </script>
   @endpush
</div>

