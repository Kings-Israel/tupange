<div class="review-wrap">
   <h1>Reviews ({{ $reviews->count() }})</h1>
   <ul class="place__comments">
      @forelse ($reviews as $review)
         <li>
            <div class="place__author">
               <div class="place__author__avatar">
                  <img src="{{ $review->user->getAvatar($review->user->avatar) }}" alt="">
               </div>
               <div class="place__author__info">
                  <span>{{ $review->user->f_name }} {{ $review->user->l_name }}</span><br>
                  <div class="place__author__star">
                     <i class="la la-star"></i>
                     <i class="la la-star"></i>
                     <i class="la la-star"></i>
                     <i class="la la-star"></i>
                     <i class="la la-star"></i>
                     <span style="width: {{ $review->getStarRating($review->rating) }}%">
                        <i class="la la-star"></i>
                        <i class="la la-star"></i>
                        <i class="la la-star"></i>
                        <i class="la la-star"></i>
                        <i class="la la-star"></i>
                     </span>
                  </div>
                  <span class="time">{{ $review->created_at->diffForHumans() }}</span>
               </div>
            </div>
            <div class="place__comments__content">
                  <p>{{ $review->comment }}</p>
            </div>
         </li>
      @empty
         <p>No Reviews</p>
      @endforelse
   </ul>
</div>
