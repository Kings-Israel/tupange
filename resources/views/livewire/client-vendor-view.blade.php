<div class="place-item layout-02 place-hover">
   <div class="place-inner">
       <div class="place-thumb">
           <a class="entry-thumb" href="{{ route('vendor.view', $vendor) }}"><img src="{{ $vendor->getCompanyLogo($vendor->company_logo) }}" alt=""></a>
       </div>
       <div class="entry-detail">
           <h3 class="place-title"><a href="{{ route('vendor.view', $vendor) }}">{{ $vendor->company_name }}</a></h3>
           <div class="address" style="margin-top: -20px">
               <i class="la la-map-marker"></i>
               <span id="location">{{ $vendor->location }}</span>
           </div>
           <div class="entry-bottom">
               <div class="place-preview">
                   <div class="place-rating">
                       <span>{{ ceil(($vendor->getVendorRating() / 5) * 100) }}</span>
                       <i class="la la-star"></i>
                       <span>({{ $vendor->reviews->count() }} Reviews)</span>
                   </div>
               </div>
           </div>
       </div>
   </div>
</div>
