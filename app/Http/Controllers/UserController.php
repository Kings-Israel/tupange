<?php

namespace App\Http\Controllers;

use App\Jobs\SendSms;
use App\Models\User;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Image;

class UserController extends Controller
{
   public function switch_profile()
   {
      $user = Auth::user();

      if($user->status == 'user') {
         // Switch to vendor profile
         $user->status = 'vendor';
         $user->save();

         return redirect()->route('vendor.dashboard');
      } else if($user->status == 'vendor') {
         $user->status = 'user';
         $user->save();

         return redirect()->route('home');
      }
   }

   public function editProfile()
   {
      $user = auth()->user();
      return view('user-profile', compact('user'));
   }

   public function updateProfile(Request $request)
   {
      $rules = [
         'phone_number' => ['required', 'max:12', 'min:9', Rule::unique('users')->ignore($request->user()->id, 'id')],
         'email' => ['required', 'string', Rule::unique('users')->ignore($request->user()->id, 'id')],
         'first_name' => ['required', 'string'],
         'last_name' => ['required', 'string']
      ];

      $messages = [
         'required' => 'Please enter a value here',
         'string' => 'Please enter a valid value'
      ];
      Validator::make($request->all(), $rules, $messages)->validate();

      if ($request->password != null) {
         // Compare current password
         if ($request->current_password == null) {
            return redirect()->back()->with('error', 'Please enter your current password');
         } elseif ($request->password != $request->password_confirmation) {
            return redirect()->back()->with('error', 'The password does not match');
         }

         if (!Hash::check($request->current_password, auth()->user()->password)) {
            return redirect()->back()->with('error', 'Current Password entered is not correct');
         }
      }

      $user = User::find($request->user_id);

      if($request->hasFile('user_avatar')) {
         $image = $request->file('user_avatar');
         $input['imagename'] = time().'.'.$image->extension();

         $filePath = storage_path('app/public/user/avatar');
         $img = Image::make($image->path());
         $img->resize(700, 464, function($const) {
            $const->aspectRatio();
         })->save($filePath.'/'.$input['imagename']);
         // $user->avatar = pathinfo($request->user_avatar->store('avatar', 'user'), PATHINFO_BASENAME);
         $user->avatar = $img->basename;
      }

      if ($user->phone_number != $request->user_phone_number) {
         $regenerated_code = mt_rand(1000, 9999);
         $user->phone_verification_code = $regenerated_code;
         $user->phone_verified_at = null;
      }

      if ($user->email != $request->user_email) {
         $user->email_verified_at = null;
      }

      $user->f_name = $request->first_name;
      $user->l_name = $request->last_name;
      $user->email = $request->email;
      $user->phone_number = $request->phone_number;
      if ($request->password != null) {
         $user->password = Hash::make($request->password);
      }

      if ($user->save()) {
         return redirect()->back()->with('success', 'Profile Updated');
      }

      return redirect()->back()->with('error', 'An error occurred. Please try again');
   }

   public function setCredentials()
   {
      return view('set-password');
   }

   public function submitCredentials(Request $request)
   {
      $rules = [
         'password' => ['required', 'confirmed'],
         'phone_number' => ['required', 'unique:users']
      ];

      Validator::make($request->all(), $rules)->validate();

      $user = Auth::user();
      $user->update([
         'password' => Hash::make($request->password),
         'phone_number' => $request->phone_number,
      ]);

      SendSms::dispatch($request->phone_number, 'Your verification code is '.$user->phone_verification_code);

      // return redirect('/verify/phonenumber')->with('success', 'Profile updated successfully');
      return redirect('/')->with('success', 'Profile updated successfully');
   }

   public function setPhoneNumber()
   {
      return view('add-phone-number');
   }

   public function submitPhoneNumber(Request $request)
   {
      Validator::make($request->all(),
         ['phone_number' => ['required', 'unique:users']],
         ['required' => 'Please enter the phone number here.'])
      ->validate();

      $user = auth()->user();
      $updatedUser = $user->update([
         'phone_number' => $request->phone_number
      ]);

      if ($updatedUser) {
         return redirect('/')->with('Phone number updated.');
      } else {
         return redirect()->back()->with('error', 'An error occurred. Try again');
      }
   }

   public function verifyPhoneForm()
   {
      if (!preg_match('/^(?:254|\+254|0)?((?:(?:7(?:(?:[01249][0-9])|(?:5[789])|(?:6[89])))|(?:1(?:[1][0-5])))[0-9]{6})$/', auth()->user()->phone_number)) {
         return redirect()->route('user.profile.edit')->with('error', 'Update your profile with a valid phone number');
      }

      if (auth()->user()->phone_verified_at == null) {
         SendSms::dispatch(auth()->user()->phone_number, 'Your verification code is '.auth()->user()->phone_verification_code);
         return view('verify-phone');
      } else {
         return redirect()->back()->with('success', 'Phone number already verified');
      }
   }

   public function verifyPhoneNumber(Request $request)
   {
      Validator::make($request->all(),
         ['code' => ['required']],
         ['code.required' => 'Please enter verification code'])
      ->validate();

      $user = auth()->user();

      if ($request->code == $user->phone_verification_code) {
         $user->phone_verified_at = now();
         $user->save();

         return redirect('/home')->with('success', 'Phone Number verified successfully');
      } else {
         return redirect()->back()->with('error', 'Incorrect verification code. Please try again.');
      }
   }
}
