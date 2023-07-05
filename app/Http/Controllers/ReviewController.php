<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Service;
use Illuminate\Http\Request;
use Auth;
use Session;

class ReviewController extends Controller
{
   //store
   public function storeReview(Request $request)
   {
      $review = new Review;
      $review->user_id    = Auth::user()->id;
      $review->service_id = $request->service_id;
      $review->comment    = $request->review;
      $review->rating     = $request->rate;
      $review->order_id   = $request->order_id;
      $review->save();

      // Get the total number of ratings
      $reviews_count = Review::where('service_id', $request->service_id)->whereDate('created_at', '<=', now()->addMonths(6))->count();

      // Get the service average rating
      $service = Service::find($request->service_id);

      //Add the new rating to the average service rating
      $added_rating = $service->service_rating + $request->rate;

      // Divide by the total number of ratings
      $new_rating = $added_rating / $reviews_count;

      // Save the new service rating
      $service->update([
         'service_rating' => $new_rating
      ]);

      Session::flash('success','Review posted');

      return redirect()->back();
   }
}
