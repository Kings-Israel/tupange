<?php

namespace App\Http\Controllers\Auth;

use App\Models\Cart;
use App\Models\Role;
use App\Models\User;
use App\Models\Event;
use App\Models\UserRole;
use App\Models\EventUser;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Notifications\UserEventNotification;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
   //  protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'f_name' => ['required', 'string', 'max:255'],
            'l_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'max:12', 'min:9', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
         
        ], [
         'f_name.required' => 'Please enter your first name',
         'l_name.required' => 'Please enter your last name',
         'email.required' => 'Please enter your email',
         'email.email' => 'Please a valid email',
         'phone_number.required' => 'Please enter your phone number',
         'password.required' => 'Please enter a password',
         'password.confirmed' => 'The passwords do not match',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'f_name' => $data['f_name'],
            'l_name' => $data['l_name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => Hash::make($data['password']),
            'phone_verification_code' => mt_rand(1000, 9999),
        ]);
    }

   /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
   public function register(Request $request)
   {
      $this->validator($request->all())->validate();

      event(new Registered($user = $this->create($request->all())));

      $this->guard()->login($user);

      if ($response = $this->registered($request, $user)) {
         return $response;
      }

      $redirectPath = redirect()->intended($this->redirectPath())->getTargetUrl();

      $event = session()->get('event');
      if ($event !== '' && $event != NULL) {
         $new_event = new Event;
         $new_event->user_id = Auth::id();
         foreach ($event as $key => $details) {
            $new_event->$key = $details;
         }
         $new_event->save();
         session()->forget('event');
         session()->flash('success', 'Event Saved. Go to My Events to view.');
      }

      $cart = session()->get('cart');
      if ($cart !== '' && $cart != NULL) {
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

      $event_registration = session()->get('event-user-registration');

      if ($event_registration !== '' && $event_registration != NULL) {
         $eventUser = EventUser::where('email', $request->email)->first();

         if ($eventUser) {
            $eventUser->user_id = $user->id;
            $eventUser->save();

            $role = Role::where('name', $eventUser->role)->first();
            $new_event = Event::find($event_registration);

            UserRole::create([
               'event_id' => $new_event->id,
               'user_id' => $user->id,
               'role_id' => $role->id
            ]);

            $user->notify(new UserEventNotification($new_event->id, $new_event->event_name, $new_event->user->f_name.' '.$new_event->user->l_name, $role->name));

            session()->forget('event-user-registration');

            $redirectPath = redirect()->route('events.show', $new_event->id)->getTargetUrl();
         }
      }

      if ($event !== '' && $event != NULL) {
         if (session()->get('search-services')) {
            $redirectPath = redirect()->route('client.services.all')->getTargetUrl();
            session()->forget('search-services');
         } else {
            $redirectPath = redirect()->route('events.show', $new_event->id)->getTargetUrl();
         }
      }

      session()->forget('event-user-login');

      return $request->wantsJson()
                  ? new JsonResponse(['redirectPath' => $redirectPath ], 201)
                  : redirect($this->redirectPath());
   }

   public function redirectTo()
   {
      return url('/home');
   }
}
