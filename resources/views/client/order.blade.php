@extends('layouts.master')
@section('title','Order')
@section('css')
   <style>
      .order_quotation_agreement {
         max-height: 100px;
         overflow-x: hidden;
         overflow-y: auto;
      }
      .entry-detail {
         min-height: 200px !important;
         display: flex !important;
         flex-direction: column !important;
         justify-content: space-between !important;
      }
      .rating {
         display: flex;
         flex-direction: row-reverse;
         float: left;
      }

      .rating > input{
         display:none;
      }

      .rating > label {
         position: relative;
         width: 1.1em;
         font-size: 2vw;
         color: #23D3D3;
         cursor: pointer;
      }

      .rating > label::before{
         content: "\2605";
         position: absolute;
         opacity: 0;
      }

      .rating > label:hover:before,
      .rating > label:hover ~ label:before {
         opacity: 1 !important;
      }

      .rating > input:checked ~ label:before{
         opacity:1;
      }

      .rating:hover > input:checked ~ label:before{
         opacity: 0.4;
      }

      .shop-details__thumb > a > img {
         border-radius: 15px;
      }

      .progress {
         height: 30px;
         font-size: 15px;
      }

      @media only screen and (max-width: 992px) {
         .rating > label {
            font-size: 6vw;
         }
         .sidebar--shop {
            padding-left: 0;
         }

         .progress {
            display: none;
         }
      }
   </style>
@endsection
@section('content')
<main id="main" class="site-main service-details">
    <div class="owner-page-wrap">
         <div class="container">
            <div class="row">
               <div class="col-lg-4 col-md-4 col-sm-12">
                  <div class="sidebar sidebar--shop sidebar--border">
                        <div class="widget widget-shadow widget-reservation">
                           <div class="shop-details__thumb">
                              <a title="{{ $order->service->service_title }}" href="{{ route('client.service.one', $order->service) }}"><img src="{{ $order->service->getCoverImage($order->service->service_image) }}" alt="Service"></a>
                           </div>
                           <br>
                           <a href="{{ route('client.service.one', $order->service) }}">
                              <h1>{{ $order->service->service_title }}</h1>
                           </a>
                           <div class="place__box place__box-overview">
                              <p class="service-description">{{ $order->service->service_description }}</p>
                              <hr>
                              <h3>Company: <strong>{{ $order->service->vendor->company_name }}</strong></h3>
                              <h3>Category: <strong>{{ $order->service->category->name }}</strong></h3>
                           </div>
                        </div><!-- .widget-reservation -->
                  </div><!-- .sidebar -->
                  </div>
                  <div class="col-lg-8 col-md-8 col-sm-12">
                     <div class="place__left">
                        <h1>Order <strong>{{ $order->order_id }}</strong></h1>
                        <hr>
                        <div class="progress @if($order->status === 'Removed' || $order->status === 'Archived') d-none @endif">
                           <div class="progress-bar site-primary-backgound-color" role="progressbar" style="width: {{ $order->statusWidth() }}%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100">1. SENT</div>
                           <div
                              class="progress-bar
                              @if($order->status === 'Received' ||
                                 $order->status === 'Paid' ||
                                 $order->status === 'Disputed' ||
                                 $order->status === 'Delivered' ||
                                 $order->status === 'Completed' ||
                                 $order->status === 'Cancelled' ||
                                 $order->status === 'Archived'
                                 )
                                 site-primary-backgound-color
                              @endif
                              @if($order->status === 'Sent')
                                 bg-secondary
                              @endif
                              @if($order->status === 'Declined' ||
                                 $order->status === 'Removed'
                                 )
                                 d-none
                              @endif"
                              role="progressbar"
                              style="width: {{ $order->statusWidth() }}%"
                              aria-valuenow="30"
                              aria-valuemin="0"
                              aria-valuemax="100"
                           >
                                 2. RECEIVED
                           </div>
                           <div class="progress-bar bg-danger
                              @if($order->status === 'Sent' ||
                                 $order->status === 'Received' ||
                                 $order->status === 'Paid' ||
                                 $order->status === 'Disputed' ||
                                 $order->status === 'Delivered' ||
                                 $order->status === 'Completed' ||
                                 $order->status === 'Cancelled' ||
                                 $order->status === 'Archived'
                                 )
                                 d-none
                              @endif"
                              role="progressbar"
                              style="width: {{ $order->statusWidth() }}%"
                              aria-valuenow="30"
                              aria-valuemin="0"
                              aria-valuemax="100"
                           >
                              2. DECLINED
                           </div>
                           <div class="progress-bar
                              @if($order->status === 'Paid' ||
                                 $order->status === 'Disputed' ||
                                 $order->status === 'Delivered' ||
                                 $order->status === 'Completed' ||
                                 $order->status === 'Archived'
                                 )
                                 site-primary-backgound-color
                              @endif
                              @if($order->status === 'Sent' || $order->status === 'Received')
                                 bg-secondary
                              @endif
                              @if($order->status === 'Declined' ||
                                 $order->status === 'Cancelled' ||
                                 $order->status === 'Removed'
                                 )
                                 d-none
                              @endif"
                              role="progressbar"
                              style="width: {{ $order->statusWidth() }}%"
                              aria-valuenow="20"
                              aria-valuemin="0"
                              aria-valuemax="100"
                           >
                              3. PAID
                           </div>
                           <div class="progress-bar
                              @if($order->status === 'Cancelled')
                                 bg-danger
                              @endif
                              @if($order->status === 'Sent' ||
                                 $order->status === 'Received' ||
                                 $order->status === 'Paid' ||
                                 $order->status === 'Declined' ||
                                 $order->status === 'Completed' ||
                                 $order->status === 'Disputed' ||
                                 $order->status === 'Removed'||
                                 $order->status === 'Delivered' ||
                                 $order->status === 'Archived')
                                 d-none
                              @endif"
                              role="progressbar"
                              style="width: {{ $order->statusWidth() }}%"
                              aria-valuenow="20"
                              aria-valuemin="0"
                              aria-valuemax="100"
                           >
                              3. CANCELLED
                           </div>
                           <div class="progress-bar
                                 @if($order->status === 'Disputed')
                                    bg-danger
                                 @endif
                                 @if($order->status === 'Sent' ||
                                    $order->status === 'Received' ||
                                    $order->status === 'Paid' ||
                                    $order->status === 'Declined' ||
                                    $order->status === 'Completed' ||
                                    $order->status === 'Cancelled' ||
                                    $order->status === 'Removed'||
                                    $order->status === 'Delivered' ||
                                    $order->status === 'Archived')
                                    d-none
                                 @endif"
                              role="progressbar"
                              style="width: {{ $order->statusWidth() }}%"
                              aria-valuenow="20"
                              aria-valuemin="0"
                              aria-valuemax="100"
                           >
                              4. DISPUTED
                           </div>
                           <div class="progress-bar
                              @if($order->status === 'Delivered' ||
                                 $order->status === 'Completed' ||
                                 $order->status === 'Archived')
                                    site-primary-backgound-color
                              @endif
                              @if ($order->status === 'Sent' ||
                                 $order->status === 'Received' ||
                                 $order->status === 'Paid')
                                    bg-secondary
                              @endif
                              @if($order->status === 'Disputed' ||
                                 $order->status === 'Declined' ||
                                 $order->status === 'Cancelled' ||
                                 $order->status === 'Removed')
                                    d-none
                              @endif"
                              role="progressbar"
                              style="width: {{ $order->statusWidth() }}%"
                              aria-valuenow="20"
                              aria-valuemin="0"
                              aria-valuemax="100"
                           >
                              4. DELIVERED
                           </div>
                           <div class="progress-bar site-primary-backgound-color
                              @if($order->status === 'Sent' ||
                                 $order->status === 'Received' ||
                                 $order->status === 'Paid' ||
                                 $order->status === 'Disputed' ||
                                 $order->status === 'Delivered')
                                    bg-secondary
                              @endif
                              @if($order->status === 'Declined' ||
                                 $order->status === 'Cancelled' ||
                                 $order->status === 'Removed')
                                    d-none
                              @endif"
                              role="progressbar"
                              style="width: {{ $order->statusWidth() }}%"
                              aria-valuenow="20"
                              aria-valuemin="0"
                              aria-valuemax="100"
                           >
                              5. COMPLETED
                           </div>
                         </div>
                        <hr>
                        <div class="place__box place__box--npd">
                           <div class="row">
                              <h1 class="col-lg-3">Pricing</h1>
                           </div>
                           @if ($order->service_pricing)
                              <div class="row">
                                 <div class="col-lg-4 col-md-6">
                                    <div class="place-item layout-02 place-hover">
                                       <div class="place-inner">
                                          <div class="entry-detail">
                                             <div class="place-type list-item">
                                                @if ($order->payment()->count() > 0)
                                                   <span style="display:flex;">Ksh. <b>{{ number_format($order->payment->amount) }}</b></span>
                                                @else
                                                   <span style="display:flex;">Ksh. <b>{{ number_format($order->service_pricing->service_pricing_price) }}</b></span>
                                                @endif
                                             </div>
                                             <h3 class="place-title" style="margin-bottom: -5px">{{ $order->service_pricing->service_pricing_title }}</h3>
                                             <div class="package-description">
                                                <p>{{ $order->service_pricing->service_pricing_description }}</p>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           @elseif($order->order_quotation)
                              <div class="col-lg-4 col-md-6">
                                 <div class="place-item layout-02 place-hover">
                                    <div class="place-inner">
                                       <div class="entry-detail">
                                          <div class="place-type list-item">
                                             @if ($order->payment()->count() > 0)
                                                <span style="display:flex;">Ksh. <b>{{ number_format($order->payment->amount) }}</b></span>
                                             @else
                                                <span style="display: flex">Ksh. <b>{{ number_format($order->order_quotation->order_pricing_price) }}</b></span>
                                             @endif
                                          </div>
                                          <h3 class="place-title" style="margin-bottom: -5px">{{ $order->order_quotation->order_pricing_title }}</h3>
                                          <div class="package-description">
                                             <p>{{ $order->order_quotation->order_pricing_agreement }}</p>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           @elseif(! $order->order_quotation && ! $order->service_pricing)
                              <h1>Select Quote</h1>
                              <livewire:select-order-quotation :order="$order" />
                           @endif
                        </div>
                     </div><!-- .place__left -->

                     <div class="place__left">
                        <div class="place__box place__box--npd">
                           <div class="row">
                              <div class="col-lg-6">
                                 <h1>Order Status</h1>
                                 <div>
                                    <h4 class="order-{{ $order->status }}">{{ $order->status }}</h4>
                                    <p>{{ $order->status_reason }}</p>
                                    <hr>
                                    <p>Order was made:</p>
                                    <h4>{{ $order->created_at->diffForHumans() }}</h4>
                                 </div>
                              </div>
                              <div class="col-lg-6">
                                 <h1>Linked Event</h1>
                                 @if ($order->event)
                                    <a href="{{ route('events.show', $order->event->id) }}">
                                       <h4>{{ $order->event->event_name }} ({{ $order->event->status }})</h4>
                                    </a>
                                 @else
                                    <form action="{{ route('client.order.link.event') }}" method="POST">
                                       <input type="hidden" name="order_id" value="{{ $order->id }}">
                                       @csrf
                                       <div class="row">
                                          <div class="col-lg-7">
                                             <select name="event" id="" class="form-control" required>
                                                <option value="">Select Event</option>
                                                @foreach ($events as $event)
                                                   <option value="{{ $event->id }}">{{ $event->event_name }} ({{ $event->status }})</option>
                                                @endforeach
                                             </select>
                                          </div>
                                          <div class="col-lg-5">
                                             <input type="submit" value="Select" class="btn">
                                          </div>
                                       </div>
                                    </form>
                                 @endif
                              </div>
                           </div>
                        </div>
                     </div><!-- .place__left -->
                     <div class="place__left">
                        <div class="entry-bottom">
                           <div class="place-price">
                              <div class="d-flex justify-content-start">
                                 @if ($order->service_pricing || $order->order_quotation)
                                    @if ($order->status == 'Received')
                                       <form action="{{ route('order.checkout') }}" method="post">
                                          @csrf
                                          <input type="hidden" name="orders[{{ $order->id }}]" value="{{ $order->id }}">
                                          <input type="submit" class="btn order-status-btn" style="background: green" value="Checkout"/>
                                       </form>
                                    @endif
                                 @endif
                                 @if ($order->status == 'Paid')
                                    <a href="{{ route('client.order.delivered', $order) }}">
                                       <button class="btn order-status-btn" style="background: green">Mark as Delivered</button>
                                    </a>
                                    @if (now()->diffInDays($order->updated_at) <= 2)
                                          @include('partials.dispute')
                                          <button class="btn order-status-btn" style="background: red" data-bs-toggle="modal" data-bs-target="#file-dispute-{{ $order->id }}">Raise Dispute</button>
                                    @endif
                                 @endif
                                 @if($order->status == 'Disputed')
                                    <a href="{{ route('client.order.dispute.resolve', $order) }}">
                                       <button class="btn order-status-btn" style="background: green">Mark Dispute as Resolved</button>
                                    </a>
                                 @endif
                                 @if($order->status == 'Declined')
                                    <a href="{{ route('client.order.delete', $order) }}">
                                       <button class="btn order-status-btn" style="background: green">Delete Order</button>
                                    </a>
                                 @endif
                                 @if ($order->status == 'Sent' || $order->status == 'Received' || $order->status == 'Declined')
                                    <a href="{{ route('client.order.cancel', $order) }}">
                                       <button class="btn order-status-btn" style="background: red">Cancel Order</button>
                                    </a>
                                 @endif
                                 <a href="{{ route('message.chat', $order->order_id) }}">
                                    <button class="btn order-status-btn">Contact Vendor</button>
                                 </a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="container review-wrap">
               <h2>Order Review</h2>
               <div class="review-wrap">
                  @if (! $order->review)
                     <p>This service can be reviewed once marked as completed.</p>
                     @if ($order->status == 'Paid' || $order->status == 'Completed' || $order->status == 'Delivered')
                        <form method="post" action="{!! route('client.reviews.store') !!}" class="mt-3 mb-3" enctype="multipart/form-data">
                           {!! csrf_field() !!}
                           <h5>Please leave a rating of the service you ordered</h5>
                           <div class="rating">
                              <input type="radio" name="rate" value="5" id="5"><label for="5">☆</label>
                              <input type="radio" name="rate" value="4" id="4"><label for="4">☆</label>
                              <input type="radio" name="rate" value="3" id="3"><label for="3">☆</label>
                              <input type="radio" name="rate" value="2" id="2"><label for="2">☆</label>
                              <input type="radio" name="rate" value="1" id="1"><label for="1">☆</label>
                            </div>
                           <textarea class="form-control" id="review" name="review" rows="2" oninput="auto_grow(this)" placeholder="Please enter your comments here....." minlength="3" required></textarea>
                           <input type="hidden" name="service_id" value="{{ $order->service->id }}">
                           <input type="hidden" name="order_id" value="{{ $order->id }}">
                           <button class="btn float-right btn-sm mt-2 submit" id="btn-save" type="submit">Submit Review</button>
                        </form>
                     @endif
                  @else
                     <ul class="place__comments">
                        <li>
                           <div class="place__author">
                              <div class="place__author__avatar">
                                 <img src="{{ $order->review->user->getAvatar($order->review->user->avatar) }}" alt="">
                              </div>
                              <div class="place__author__info">
                                 <span>{!! $order->review->user->name !!}</span>
                                 <div class="place__author__star">
                                    <i class="la la-star"></i>
                                    <i class="la la-star"></i>
                                    <i class="la la-star"></i>
                                    <i class="la la-star"></i>
                                    <i class="la la-star"></i>
                                    <span style="width: {{ $order->review->getStarRating($order->review->rating) }}%">
                                       <i class="la la-star"></i>
                                       <i class="la la-star"></i>
                                       <i class="la la-star"></i>
                                       <i class="la la-star"></i>
                                       <i class="la la-star"></i>
                                    </span>
                                 </div>
                                 <span class="time">{!! date('F d, Y', strtotime($order->review->created_at)) !!}</span>
                              </div>
                           </div>
                           <div class="place__comments__content">
                              <p>{!! $order->review->comment !!}</p>
                           </div>
                        </li>
                     </ul>
                  @endif
               </div>
            </div>
      </div>
</main><!-- .site-main -->
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
@endsection
