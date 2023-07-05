<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSuspended
{
   /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
    * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    */
   public function handle(Request $request, Closure $next)
   {
      if (auth()->check() && auth()->user()->is_suspended === true) {
         Auth::logout();

         $request->session()->invalidate();

         $request->session()->regenerateToken();

         return redirect()->route('home')->with('error', 'Your account has been suspended. Please contact Admin');
      }

      return $next($request);
   }
}
