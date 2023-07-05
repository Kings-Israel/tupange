<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{
   public function index()
   {
      $about = DB::table('about_us_contents')->where('id', 1)->first();
      // dd($about);
      return view('layouts.about', compact('about'));
   }
}
