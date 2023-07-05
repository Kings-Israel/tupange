<main id="main" class="site-main">
   <div class="site-content owner-content">
       <div class="container">
           <div class="member-place-wrap">
               <div class="member-wrap-top">
                   <h2>My Services</h2>
               </div><!-- .member-wrap-top -->
               <div class="vendor-services">
                  <div class="row member-filter">
                     <div class="col-lg-2 col-md-12 col-sm-12">
                        <div class="field-select">
                           <select name="perPage" wire:model="perPage">
                              <option value="1">Show 1</option>
                              <option value="5">Show 5</option>
                              <option value="10">Show 10</option>
                              <option value="20">Show 20</option>
                              <option value="40">Show 40</option>
                           </select>
                           <i class="la la-angle-down"></i>
                        </div>
                     </div>
                     <div class="col-lg-2 col-md-12 col-sm-12">
                        <div class="field-select">
                           <select name="category" wire:model="category">
                              <option value="All">All categories</option>
                              @foreach ($categories as $category)
                                 <option value="{{ $category->name }}">{{ $category->name }}</option>
                              @endforeach
                           </select>
                           <i class="la la-angle-down"></i>
                        </div>
                     </div>
                     <div class="col-lg-2 col-md-12 col-sm-12">
                        <div class="field-select">
                           <select name="status" wire:model="status">
                              <option value="All">All</option>
                              @foreach ($statuses as $status)
                                    <option value="{{ $status->name }}">{{ $status->name }}</option>
                              @endforeach
                           </select>
                           <i class="la la-angle-down"></i>
                        </div>
                     </div>
                     <div class="col-lg-2 col-md-12 col-sm-12">
                        <div class="field-group field-input">
                           <input wire:model="search" type="text" id="service-title-search" name="s" placeholder="Search Title">
                        </div><!-- .search__input -->
                     </div>
                     <div class="col-lg-2 col-md-6 col-sm-6">
                        @if ($vendor->status == 'Suspended')
                           <span class="right-header__status" style="color: red">Account is Inactive/Suspended</span>
                        @else
                           <a href="{{ url('/vendor/service/add') }}">
                              <div class="right-header__button btn btn-sm">
                                 <i class="la la-plus"></i>
                                 Add Service
                              </div>
                           </a>
                        @endif
                     </div>
                     <div class="col-lg-2 col-md-6 col-sm-6">
                        @if ($vendor->status != 'Suspended')
                           @if ($vendor->hasPausedAllServices())
                              <a href="{{ route('vendor.services.resume.all') }}" class="right-header__button btn btn-sm btn-info" style="background: #F58C1C">
                                 Resume All
                              </a>
                           @endif
                           @if ($services->count() && !$vendor->hasPausedAllServices())
                              @include('partials.vendor-pause-services')
                              <div class="right-header__button btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#pause-services" id="pause-services" style="background-color: #1DA1F2">
                                 Pause All
                              </div>
                           @endif
                        @endif
                     </div>
                  </div>
                  <br>
                  @if ($services->count())
                     <table class="member-place-list owner-booking table-responsive">
                        <thead>
                              <tr>
                                 <th>#</th>
                                 <th>Cover Image</th>
                                 <th>Service Title</th>
                                 <th>Category</th>
                                 <th>Status</th>
                                 <th>Actions</th>
                              </tr>
                        </thead>
                        <tbody>
                           @foreach ($services as $service)
                              @include('partials.pause-service')
                              @include('partials.vendor-delete-service')
                              <tr>
                                 <td data-title="ID">{{ $i++ }}</td>
                                 <td data-title="Cover Image" class="service-img">
                                    <a href="{{ route('vendor.service.edit.view', $service) }}">
                                       <img src="{{ $service->getCoverImage($service->service_image) }}" alt="{{ $service->service_title }}" onerror="this.onerror=null; this.src='{{ $service->vendor->getCompanyLogo($service->vendor->company_logo) }}'" style="border-radius: 5px;">
                                    </a>
                                 </td>
                                 <td data-title="Service Title">
                                    <a href="{{ route('vendor.service.edit.view', $service) }}">
                                       <b>{{ $service->service_title }}</b>
                                    </a>
                                 </td>
                                 <td data-title="Category">{{ $service->getCategory($service->category_id)->name }}</td>
                                 <td data-title="Status">{{ $service->getServiceStatus($service->service_status_id)->name }}</td>
                                 <td>
                                    <a href="{{ route('vendor.service.edit.view', $service) }}" class="edit" title="View and Edit"><i class="la la-eye"></i></a>
                                    @if ($service->service_status_id == 3 || $service->service_status_id == 2)
                                       <span class="delete" title="Restore Service" wire:click="restoreService({{ $service }})"><i class="la la-play"></i></span>
                                    @else
                                       <span class="view" title="Pause Service" data-bs-toggle="modal" data-bs-target="#pause-service-{{ $service->id }}"><i class="la la-pause"></i></span>
                                       <span title="Delete Service" data-bs-toggle="modal" data-bs-target="#delete-service-{{ $service->id }}"><i class="la la-trash-alt"></i></span>
                                    @endif
                                 </td>
                              </tr>
                           @endforeach
                        </tbody>
                     </table>
                     {{ $services->links() }}
                  @else
                     <p>No Services Found</p>
                  @endif
               </div>
           </div>
       </div>
   </div>
</main>
