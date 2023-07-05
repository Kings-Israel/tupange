<?php

namespace App\Http\Controllers;

use App\Models\CustomerReview;
use Illuminate\Http\Request;

class CustomerReviewController extends Controller
{
   public function review(Request $request)
   {
      $review = CustomerReview::create([
         'user_id' => auth()->user()->id,
         'review' => $request->customer_review
      ]);

      if ($review) {
         return redirect()->back()->with('success', 'Thank you for the feedback');
      }

      return redirect()->back()->with('error', 'Please try again.');

   }
}
