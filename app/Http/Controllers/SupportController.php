<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SupportController extends Controller
{
   public function index()
   {
      $faqs = DB::table('faqs')->get();
      // dd($faqs);
      return view('layouts.faqs', compact('faqs'));
   }
}
