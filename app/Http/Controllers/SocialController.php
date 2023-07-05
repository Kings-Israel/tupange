<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Models\Event;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use App\Notifications\ClientNotification;
use Illuminate\Support\Facades\Validator;
use App\Notifications\PasswordNotification;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\Validator as ValidationValidator;

class SocialController extends Controller
{
   use AuthenticatesUsers;

   public function __construct()
   {
      $this->middleware('guest')->except('logout');
   }

   public function facebookRedirect()
   {
      return Socialite::driver('facebook')->redirect();
   }

   public function facebookLogin()
   {
      try {
         $user = Socialite::driver('facebook')->scopes(['email'])->stateless()->user();

         $isUser = User::where('fb_id', $user->id)->first();

         if($isUser) {
            Auth::login($isUser);

            // Check if user was trying to add an event and save
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

            // Check if there are items in cart
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
               session()->forget('cart');
            }

            session()->forget('event-user-login');

            if (Auth::user()->status == 'vendor') {
               return redirect('/vendor/dashboard');
            } elseif ($event) {
               if (session()->get('search-services')) {
                  session()->forget('search-services');
                  return redirect()->route('client.services.all');
               } else {
                  return redirect()->route('events.show', $new_event->id);
               }
            } elseif ($cart) {
               return redirect()->route('client.cart');
            }

            return redirect('/');
         } else {
            $findUser = User::where('email', $user->email)->first();

            if ($findUser) {
               $findUser->update([
                  'fb_id' => $user->id
               ]);

               Auth::login($findUser);

               // Check if user was trying to add an event and save
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

               // Check if there are items in cart
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
                  session()->forget('cart');
               }

               session()->forget('event-user-login');

               if (Auth::user()->status == 'vendor') {
                  return redirect('/vendor/dashboard');
               } elseif ($event) {
                  if (session()->get('search-services')) {
                     session()->forget('search-services');
                     return redirect()->route('client.services.all');
                  } else {
                     return redirect()->route('events.show', $new_event->id);
                  }
               } elseif ($cart) {
                  return redirect()->route('client.cart');
               }

               return redirect('/');
            }

            $usersName = explode(' ', $user['name']);

            $createUser = User::create([
               'f_name' => $usersName[0],
               'l_name' => $usersName[1] ? $usersName[1] : '',
               'email' => $user->email,
               'fb_id' => $user->id,
               'password' => Hash::make($user->email),
               'phone_verification_code' => mt_rand(1000, 9999)
            ]);

            $createUser->notify(new PasswordNotification($createUser->email));

            Auth::login($createUser);

            // Check if user was trying to add an event and save
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

            // Check if there are items in cart
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
               session()->forget('cart');
            }

            session()->forget('event-user-login');

            // return redirect('/set-credentials');
            if ($event) {
               if (session()->get('search-services')) {
                  session()->forget('search-services');
                  return redirect()->route('client.services.all');
               } else {
                  return redirect()->route('events.show', $new_event->id);
               }
            } elseif ($cart) {
               $redirectPath = redirect()->route('client.cart');
            }
            return redirect('/');
         }
      } catch (Exception $exception) {
         return back()->with('error', 'An error occured');
      }
   }

   public function redirectToGoogle()
   {
      return Socialite::driver('google')->redirect();
   }

   public function handleGoogleCallback()
   {
      try {
         $user = Socialite::driver('google')->stateless()->user();

         $finduser = User::where('google_id', $user->id)->first();

         if($finduser){
            Auth::login($finduser);

            // Check if user was trying to add an event and save
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

            // Check if there are items in cart
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
               session()->forget('cart');
            }

            session()->forget('event-user-login');

            if (Auth::user()->status == 'vendor') {
               return redirect('/vendor/dashboard');
            } elseif ($event) {
               if (session()->get('search-services')) {
                  session()->forget('search-services');
                  return redirect()->route('client.services.all');
               } else {
                  return redirect()->route('events.show', $new_event->id);
               }
            } elseif ($cart) {
               return redirect()->route('client.cart');
            }

           return redirect('/');
         } else {
            $findUser = User::where('email', $user->email)->first();

            if ($findUser) {
               $findUser->update([
                  'google_id' => $user->id
               ]);

               Auth::login($findUser);

               // Check if user was trying to add an event and save
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

               // Check if there are items in cart
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
                  session()->forget('cart');
               }

               session()->forget('event-user-login');

               if (Auth::user()->status == 'vendor') {
                  return redirect('/vendor/dashboard');
               } elseif ($event) {
                  if (session()->get('search-services')) {
                     session()->forget('search-services');
                     return redirect()->route('client.services.all');
                  } else {
                     return redirect()->route('events.show', $new_event->id);
                  }
               } elseif ($cart) {
                  redirect()->route('client.cart');
               }

               return redirect('/');
            }

            $newUser = User::create([
               'f_name' => $user['given_name'],
               'l_name' => $user['family_name'],
               'email' => $user->email,
               'password' => Hash::make($user->email),
               'google_id' => (string) $user->id,
               'phone_verification_code' => mt_rand(1000, 9999),
            ]);

            $newUser->notify(new PasswordNotification($newUser->email));

            Auth::login($newUser);

            // Check if user was trying to add an event and save
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

            // Check if there are items in cart
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
               session()->forget('cart');
            }

            session()->forget('event-user-login');

            // return redirect('/set-credentials');
            if ($event) {
               if (session()->get('search-services')) {
                  session()->forget('search-services');
                  return redirect()->route('client.services.all');
               } else {
                  return redirect()->route('events.show', $new_event->id);
               }
            } elseif ($cart) {
               $redirectPath = redirect()->route('client.cart');
            }
            return redirect('/');
         }
      } catch (Exception $e) {
         return redirect('auth/google')->with('error', 'An error occurred.');
      }

   }
}
