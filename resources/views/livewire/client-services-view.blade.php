<div>
   <div class="filter-group m-1">
      <div class="container">
         <form action="#" class="site__search__form mb-2 mf-right" wire:submit="searchServices" method="GET">
            <div class="row listing-box">
               <div class="col-lg-4 field-select">
                  <select name="category" wire:model="category" class="form-control">
                     <option value="All">All categories</option>
                     @foreach ($categories as $category)
                        <option value="{{ $category->name }}" class="mr-1">{{ $category->name }}</option>
                     @endforeach
                  </select>
               </div>
               <div class="col-lg-4">
                  <div class="field-group field-input">
                     <input wire:model="search" type="text" name="title" placeholder="Search Title">
                  </div><!-- .search__input -->
               </div>
               <div class="col-lg-4">
                  <div class="field-group field-select">
                     <select name="location" wire:model="location" class="form-control">
                        <option value="All">Select County</option>
                        <option value="All">All Counties</option>
                        @foreach ($counties as $county)
                           <option value="{{ $county['name'] }}">{{ $county['name'] }}</option>
                        @endforeach
                     </select>
                  </div><!-- .search__input -->
               </div>
            </div>
            @if (! collect($selectedCategories)->isEmpty() || $date != '' || $year != '' )
               <div class="service-filters">
                  @if ($year != '')
                     <span class="service-category-filter badge">{{ $year }} <span wire:click="removeYearFilter({{ $year }})" style="cursor: pointer"><i class="las la-times"></i></span></span>
                  @elseif($date != '')
                     <span class="service-category-filter badge">{{ $date }} <span wire:click="removeDateFilter({{ $date }})" style="cursor: pointer"><i class="las la-times"></i></span></span>
                  @endif
                  @foreach ($selectedCategories as $key => $value )
                     <span class="service-category-filter badge">{{ $value }} <span wire:click="removeCategory({{ $key }})" style="cursor: pointer"><i class="las la-times"></i></span></span>
                  @endforeach
               </div>
            @endif
         </form><!-- .search__form -->

         @if (! collect($selectedCategories)->isEmpty() || $date != '' || $year != '' || $search != '' || $location != '' )
            <a href="" wire:click.prevent="clearAllFilters" class="badge" style="background-color: #F58C1C; color: white">Clear All Filters</a>
         @endif
      </div>
   </div><!-- .filter-group -->

   <div class="archive-city layout-02">
      <div class="container">
         <div class="main-primary">
            <div class="top-area top-area-filter">
               <div class="filter-left">
                  <span class="result-count"><span class="count">{{ $services->count() }}</span> results</span>
               </div>
            </div>

            <div class="area-places layout-4col">
               @forelse ($services as $service)
                  <div class="place-item layout-02 place-hover">
                     <div class="place-inner">
                        <div class="place-thumb hover-img">
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
                              @if (auth()->user()->id != $service->vendor->user_id)
                                 <livewire:service-favorite :service="$service" :watched-file="$service->id" :key="now().$service->id" />
                              @endif
                           @endauth
                        </div>
                        <div class="entry-detail">
                           <div class="entry-head">
                              <div class="place-type list-item">
                                 <span>{{ $service->getCategory($service->category_id)->name }}</span>
                              </div>
                              <div class="place-city">
                                 <span>{{ $service->vendor->company_name }}</span>
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
                                    {{-- <livewire:service-cart :service="$service" :watched-file="$service->id" :key="$service->id" /> --}}
                                    <div class="place-price" style="cursor: pointer">
                                       <a title="View Pricing Packages for the service" href="{{ route('client.service.one', $service) }}" style="font-size: 17px;">View Packages</a>
                                    </div>
                                 @endif
                              @else
                                 {{-- <a title="Cart" href="{{ route('client.add-to-cart', $service) }}"><i class="fa fa-shopping-cart" style="font-size: 14px; cursor: pointer"></i></a> --}}
                                 <a title="View Pricing Packages for the service" href="{{ route('client.service.one', $service) }}" style="font-size: 17px;">View Packages</a>
                              @endauth
                           </div>
                        </div>
                     </div>
                  </div>
               @empty
                  <p class="no-services-found-text">No services found</p>
               @endforelse
            </div>
            {{ $services->links() }}
         </div><!-- .main-primary -->
      </div><!-- .col-left -->
   </div><!-- .archive-city -->
</div>
