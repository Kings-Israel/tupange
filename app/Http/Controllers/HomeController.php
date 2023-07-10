<?php

namespace App\Http\Controllers;

use App\Enums\EventTypes;
use App\Jobs\SendSms;
use App\Mail\SendDispute;
use App\Models\Category;
use App\Models\CustomerReview;
use App\Models\EventGuestDetail;
use App\Models\EventProgram;
use App\Models\EventProgramActivity;
use App\Models\MpesaPayment;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Register;
use App\Notifications\EventProgramNotification;
use Carbon\Carbon;
use App\Rules\PhoneNumber;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Spatie\Searchable\Search;
use PDF;
use Illuminate\Support\Str;

class HomeController extends Controller
{
   public function index()
   {
      $services = Service::orderBy('service_rating', 'DESC')->where('service_status_id', 1)->where('featured', 1)->where('status', 1)->inRandomOrder()->get()->take(8);
      $categories = Category::all();
      $services->load('service_images', 'service_pricing');
      $vendors = Vendor::all();
      $testimonials = CustomerReview::inRandomOrder()->limit(10)->get();
      $eventTypes = EventTypes::labels();
      $cart = session()->get('cart');
      if ($cart == null) {
         $cart = [];
      }
      return view('welcome', compact('services', 'categories', 'vendors', 'testimonials', 'eventTypes', 'cart'));
   }

   public function viewVendor($id)
   {
      $vendor = Vendor::find($id);
      $vendor->load(['services' => function($query) {
         $query->where('service_status_id', 1);
      }]);

      $vendor->loadCount(['orders' => function($query) {
         $query->where('orders.status', 'Completed')->orWhere('orders.status', 'Paid')->orWhere('orders.status', 'Delivered')->orWhere('orders.status', 'Archived');
      }]);

      $services = $vendor->services;
      $servicesCount = $services->count();
      $servicesRating = $services->pluck('service_rating')->sum();
      $vendorRating = 0;
      if ($vendor->services->count()) {
         $vendorRating = $servicesRating / $servicesCount;
      }
      return view('vendor.view', compact('vendor', 'vendorRating'));
   }

   public function searchServices(Request $request)
   {
      $category = $request->category;
      if ($request->category == 'Other Services' || $request->category == null) {
         $category = 'All';
      }

      return redirect()->route('client.services.all', [
         'category' => $category,
         'month' => $request->when == null ? '' : Carbon::parse($request->when)->format('m'),
         'day' => $request->when == null ? '' : Carbon::parse($request->when)->format('d'),
         'year' => $request->when == null ? '' : Carbon::parse($request->when)->format('Y')
      ]);
   }

   public function searchEvents(Request $request)
   {
      $event = $request->event;
      if ($request->category == 'Other Services') {
         $category = 'All';
      }

      return redirect()->route('client.services.all', $category);
   }

   public function eventUserRegister($id, $email)
   {
      $user = User::where('email', $email)->first();
      if ($user) {
         if (auth()->check()) {
            return redirect()->route('events.show', $id)->with('sucsess', 'You have been assigned a new role');
         }

         session()->flash('success', 'Login to view Event');

         session()->put('event-user-login', true);

         return redirect()->route('home')->with(['success' => 'Please login to view your roles']);
      }

      session()->put('event-user-login', true);

      session()->put('event-user-registration', $id);

      session()->flash('success', 'Please Register to view Event');

      return redirect()->route('home');
   }

   public function ticket($guest)
   {
      // $guest = Register::where('barcode', $guest)->first();
      // $names = explode(' ', $guest->names);
      // EventGuestDetail::firstOrCreate(
      //    ['barcode' => $guest->barcode],
      //    ['event_id' => $guest->event->id,
      //    'status' => 'Attended',
      //    'first_name' => $names[0],
      //    'last_name' => $names[1] ? $names[1] : '',
      //    'type' => 'Ticket Admission',
      //    'phone_number' => $guest->phone_number ? $guest->phone_number : null,
      //    'email' => $guest->email ? $guest->email : null,
      //    'address' => $guest->address ? $guest->address : null,
      //    'company' => $guest->company ? $guest->company : null,
      //    'ticketSent' => true,]
      // );
      $guest = EventGuestDetail::where('barcode', $guest)->first();

      return view('partials.ticket', compact('guest'));
   }

   public function guestTicket($guest)
   {
      $guest = EventGuestDetail::where('barcode', $guest)->first();

      if ($guest) {
         $guest->update([
            'status' => 'Attended'
         ]);
      }

      return view('partials.guest-ticket', compact('guest'));
   }

   public function createProgram()
   {
      $eventTypes = EventTypes::labels();
      return view('create-program', compact('eventTypes'));
   }

   public function submitProgram(Request $request)
   {
      $rules = [
         'event_name' => 'required',
         'event_type' => 'required',
         'event_type_custom' => ['required_if:event_type,Other'],
         'event_start_date' => 'required',
         'event_start_time' => 'required',
         'event_end_date' => 'required',
         'event_end_time' => 'required',
         'direction_description' => 'required',
         'first_name' => ['required'],
         'last_name' => ['required'],
         'email' => 'required',
         'phone_number' => ['required']
      ];

      $messages = [
         'event_name.required' => 'Please enter the event name',
         'event_type.required' => 'Please select the event type',
         'event_start_date.required' => 'Enter the date the event starts',
         'event_start_time.required' => 'Enter the time the event starts',
         'event_end_date.required' => 'Enter the date the event ends',
         'event_end_time.required' => 'Enter the time the event ends',
         'direction_description.required' => 'Enter the directions to the event. E.g. a landmark',
         'first_name' => 'Enter your first name',
         'last_name' => 'Enter your last name',
         'email.required' => 'Please enter your email address',
      ];

      if ($request->activity[0] != null) {
         for ($i=0; $i < count($request->activity); $i++) {
            $rules['action.'.$i] = 'required';
            $rules['activity_start_time.'.$i] = 'required';
            $rules['activity_end_time.'.$i] = 'required';
         }
         $messages['action.*.required'] = 'Enter the action in this activity';
         $messages['activity_start_time.*.required'] = 'Activity start at';
         $messages['activity_end_time.*.required'] = 'Activity ends at';
      }

      if ($request->program_contact_name[0] != null) {
         for ($i=0; $i < count($request->program_contact_name); $i++) {
            $rules['program_contact_phone.'.$i] = ['required'];
         }
         $messages['program_contact_phone.*.required'] = 'Enter the phone number';
      }

      $validator = Validator::make($request->all(), $rules, $messages);

      if ($validator->fails()) {
         return response()->json($validator->errors(), 422);;
      }

      // Get location string
      $event_location = Http::get('https://maps.googleapis.com/maps/api/geocode/json?place_id='.$request->place_id.'&key='.config('services.maps.partial_key'));
      $event_location = json_decode($event_location->body());

      $lat = $event_location->results[0]->geometry->location->lat;
      $long = $event_location->results[0]->geometry->location->lng;

      // Log in the user or create account
      $user = User::firstOrCreate(
         ['email' => $request->email],
         [
            'f_name' => $request->first_name,
            'l_name' => $request->last_name,
            'phone_number' => $request->phone_number,
            'password' => Hash::make('secretpassword'),
            'email_verified_at' => now()
         ],
      );

      Auth::login($user);

      $eventProgram = new EventProgram;
      if ($request->event_id != null) {
         $eventProgram->event_id = $request->event_id;
      }

      if (auth()->check()) {
         $eventProgram->user_id = auth()->user()->id;
      }
      $eventProgram->event_name = $request->event_name;
      $eventProgram->event_type = $request->event_type != 'Other' ? $request->event_type : $request->event_type_custom;
      $eventProgram->start_date = $request->event_start_date.' '.$request->event_start_time;
      $eventProgram->end_date = $request->event_end_date.' '.$request->event_end_time;
      $eventProgram->venue_location_lat = $lat;
      $eventProgram->venue_location_long = $long;
      $eventProgram->venue_location = $request->has('place_id') && $request->place_id != NULL ? $event_location->results[0]->formatted_address : $request->event_location;
      $eventProgram->direction_description = $request->direction_description;
      if ($request->program_contact_name[0] != null) {
         $contacts = [];
         for ($i=0; $i < count($request->program_contact_name); $i++) {
            array_push($contacts, [$request->program_contact_name[$i] => $request->program_contact_phone[$i]]);
         }
         $eventProgram->contacts = $contacts;
      }

      $eventProgram->save();

      if ($request->activity[0] != null) {
         foreach ($request->activity as $key => $value) {
            EventProgramActivity::create([
               'event_program_id' => $eventProgram->id,
               'activity' => $request->activity[$key],
               'actions' => $request->action[$key],
               'start_time' => $request->activity_start_time[$key],
               'end_time' => $request->activity_end_time[$key]
            ]);
         }
      }

      $account_reference = Str::upper(Str::random(3)).time().Str::upper(Str::random(3));
      $phone_number = substr($request->phone_number, -9);
      $phone_number = '254'.$phone_number;

      // Prompt Mpesa Payment
      $mpesa = new MpesaPaymentController();
      $results = $mpesa->stkPush(
         $phone_number,
         '1',
         route('program.stk.push.callback'),
         $account_reference,
         'Payment of Program Download'
      );

      if ($results['response_code'] != null) {
         $eventProgram->update([
            'mpesa_checkout_request_id' => $results['checkout_request_id']
         ]);
         MpesaPayment::create([
            'order_id' => $account_reference,
            'account' => $account_reference,
            'checkout_request_id' => $results['checkout_request_id'],
            'phone' => $phone_number
         ]);

         $redirectPath = redirect()->route('client.program.show', $eventProgram)->with('success', 'Please complete the payment on your phone then refresh this page.')->getTargetUrl();
         return $request->wantsJson()
                  ? new JsonResponse(['redirectPath' => $redirectPath], 200)
                  : redirect()->route('client.program.show', $eventProgram)->with('success', 'Please complete the payment on your phone then refresh this page.');
      } else {
         $redirectPath = redirect()->back()->with('error', 'An error occured. Please try again')->getTargetUrl();
         return $request->wantsJson()
                  ? new JsonResponse(['redirectPath' => $redirectPath], 200)
                  : redirect()->back()->with('error', 'An error occured. Please try again');
      }
   }

   public function programPaymentCallback(Request $request)
   {
      $callbackJSONData = file_get_contents('php://input');
      info($callbackJSONData);
      $callbackData = json_decode($callbackJSONData);
      $checkout_request_id = $callbackData->Body->stkCallback->CheckoutRequestID;
      $mpesa_receipt_number = $callbackData->Body->stkCallback->CallbackMetadata->Item[1]->Value;

      $eventProgram = EventProgram::where('mpesa_checkout_request_id', $checkout_request_id)->first();

      $eventProgram->update([
         'payment_id' => $mpesa_receipt_number,
         'canDownload' => true
      ]);

      $eventProgram->user->notify(new EventProgramNotification($eventProgram, 'success'));
      SendSms::dispatch($eventProgram->user->phone_number, 'The event program, '. $eventProgram->event_name.', can now be downloaded and shared');
   }

   public function downloadProgram()
   {
      $pdf = PDF::loadView('program.home-pdf', ['program' => session()->get('program')]);

      return $pdf->download(session()->get('program')['event_name'].'.pdf');
   }

   public function search(Request $request)
   {
      if ($request->search == '' || $request->search == null) {
         return back()->with('error', 'Please enter description to search..');
      }
      $results = (new Search())
                     ->registerModel(Vendor::class, 'company_name')
                     ->registerModel(Service::class, 'service_title')
                     ->search($request->search);

      $map_locations = [];
      foreach ($results as $result) {
         if ($result->type == 'services') {
            $map_locations[$result->searchable->service_title] = ['lat' => $result->searchable->service_location_lat, 'long' => $result->searchable->service_location_long];
         } else {
            $location = explode(',', $result->searchable->location);

            // Format longitude (remove the ending bracket)
            substr($location[1], 0, -1);

            $lat = (double) ltrim($location[0], $location[0][0]);
            $long = (double) ltrim($location[1], $location[1][0]);

            $map_locations[$result->searchable->company_name] = ['lat' => $lat, 'long' => $long];
         }
      }
      return view('search-results', compact('results', 'map_locations'));
   }

   public function fileDispute(Request $request)
   {
      $this->validate($request, [
         'name' => ['required'],
         'email' => ['required', 'email'],
         'comments' => ['required']
      ]);

      Mail::to(env('MAIL_FROM_ADDRESS'))->send(new SendDispute($request->name, $request->email, $request->comments));

      return request()->wantsJson() ? new JsonResponse(['message' => 'Comments saved successfully. Thank you for the feedback'], 200) : redirect()->back()->with('success', 'Comments saved successfully. Thank you for the feedback');
   }

   /**
    *
    * View Favorited Services
    */
   public function favorites()
   {
      return view('client.favorited-services');
   }
}
