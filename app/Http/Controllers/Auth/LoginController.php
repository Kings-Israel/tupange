<?php

namespace App\Http\Controllers\Auth;

use App\Models\Cart;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/';

    protected function sendLoginResponse(Request $request)
    {
      $credentials = $request->validate([
         'email' => ['required', 'email'],
         'password' => ['required'],
      ]);

      if (!Auth::attempt($credentials)) {
         return $request->wantsJson()
                  ? new JsonResponse(['message' => 'Invalid Credentials'], 422)
                  : redirect()->intended($this->redirectPath());
      }

      $request->session()->regenerate();

      $this->clearLoginAttempts($request);

      if ($response = $this->authenticated($request, $this->guard()->user())) {
          return $response;
      }

      Auth::logoutOtherDevices($request->password);

      $event = session()->get('event');
      if ($event) {
         $new_event = new Event;
         $new_event->user_id = Auth::id();
         foreach ($event as $key => $details) {
            $new_event->$key = $details;
         }
         $new_event->save();
         session()->forget('event');
      }

      $cart = session()->get('cart');
      if ($cart) {
         foreach ($cart as $key => $pricing) {
            $exists = Cart::where([
                  ['user_id', Auth::user()->id],
                  ['service_id', $key],
                  ['service_pricing_id', $pricing['pricing']]
            ])->exists();

            if (!$exists) {
               Cart::create([
                  'user_id' => Auth::user()->id,
                  'service_id' => $key,
                  'service_pricing_id' => $pricing['pricing'] ? $pricing['pricing'] : NULL,
               ]);
            }
         }
      }

      $redirectPath = redirect()->intended($this->redirectPath())->getTargetUrl();

      if ($event) {
         if (session()->get('search-services')) {
            $redirectPath = redirect()->route('client.services.all')->getTargetUrl();
            session()->forget('search-services');
         } else {
            $redirectPath = redirect()->route('events.show', $new_event->id)->getTargetUrl();
         }
      }
      
      if ($cart) {
         $redirectPath = redirect()->route('client.cart')->getTargetUrl();
      }

      session()->forget('event-user-login');

      return $request->wantsJson()
                  ? new JsonResponse(['redirectPath' => $redirectPath ], 200)
                  : redirect()->intended($this->redirectPath());
    }

    protected function redirectTo()
    {
        if (Auth::check() && Auth::user()->status == 'vendor') {
            return '/vendor/dashboard';
        }
    
        return '/';
    }
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
      session(['url.intended' => url()->previous()]);
      $this->redirectTo = session()->get('url.intended');
      $this->middleware('guest')->except('logout');
    }
}
