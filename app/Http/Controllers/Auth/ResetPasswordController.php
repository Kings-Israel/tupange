<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
   /*
   |--------------------------------------------------------------------------
   | Password Reset Controller
   |--------------------------------------------------------------------------
   |
   | This controller is responsible for handling password reset requests
   | and uses a simple trait to include this behavior. You're free to
   | explore this trait and override any methods you wish to tweak.
   |
   */

   use ResetsPasswords;

   /**
    * Where to redirect users after resetting their password.
    *
    * @var string
    */
   //  protected $redirectTo = RouteServiceProvider::HOME;

   public function __construct()
   {
      $this->middleware('guest');
   }

   public function enterEmail()
   {
      return view('layouts.forgot-password');
   }

   public function confirmEmail(Request $request)
   {
      $request->validate(['email' => 'required|email']);

      $status = Password::sendResetLink(
         $request->only('email')
      );

      session()->put('success', 'Password Reset Email has been sent.');

      return $status === Password::RESET_LINK_SENT
                  ? back()
                  : back()->withErrors(['email' => __($status)]);
   }

   public function resetPassword($token)
   {
      return view('auth.passwords.reset')->with('token', $token);
   }

   public function passwordUpdate(Request $request)
   {
      $request->validate([
         'token' => 'required',
         'email' => 'required|email',
         'password' => 'required|min:8|confirmed',
      ]);

      $status = Password::reset(
         $request->only('email', 'password', 'password_confirmation', 'token'),
         function ($user, $password) {
               $user->forceFill([
                  'password' => Hash::make($password)
               ])->setRememberToken(Str::random(60));

               $user->save();

               event(new PasswordReset($user));
         }
      );

      $redirectUrl = 'home';

      return $status === Password::PASSWORD_RESET
                  ? redirect()->route($redirectUrl)->with('success', __($status))
                  : back()->withErrors(['email' => [__($status)]]);
   }
}
