<?php

namespace App\Http\Controllers;

use PDF;
use App\Jobs\SendSms;
use App\Models\Event;
use App\Helpers\Pesapal;
use App\Enums\EventTypes;
use App\Rules\PhoneNumber;
use Illuminate\Support\Str;
use App\Models\EventProgram;
use Illuminate\Http\Request;
use App\Models\PesapalPayment;
use App\Jobs\ShareEventProgram;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\EventProgramActivity;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Models\PesapalNotificationUrl;
use Illuminate\Support\Facades\Validator;
use App\Notifications\EventProgramNotification;
use App\Http\Controllers\MpesaPaymentController;

class EventProgramController extends Controller
{
   public function index()
   {
      return view('program.index');
   }

   public function create($id = null)
   {
      $eventTypes = EventTypes::labels();
      if ($id != null) {
         $event = Event::find($id);
         if ($event) {
            return view('program.create')->with(['event' => $event, 'eventTypes' => $eventTypes]);
         }
      }
      $event = null;
      return view('program.create', compact('event', 'eventTypes'));
   }

   public function store(Request $request)
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
      ];

      $messages = [
         'event_name.required' => 'Please enter the event name',
         'event_type.required' => 'Please select the event type',
         'event_type_custom' => 'Please enter the event type',
         'event_start_date.required' => 'Enter the date the event starts',
         'event_start_time.required' => 'Enter the time the event starts',
         'event_end_date.required' => 'Enter the date the event ends',
         'event_end_time.required' => 'Enter the time the event ends',
         'direction_description.required' => 'Enter the directions to the event. E.g. a landmark'
      ];

      if (gettype($request->activity) === 'array' && count($request->activity) > 0) {
         foreach ($request->activity as $key => $activity) {
            $rules['activity.'.$key] = 'required';
            $rules['action.'.$key] = 'required';
            $rules['activity_start_time.'.$key] = 'required';
            $rules['activity_end_time.'.$key] = 'required';
         }
         $messages['activity.*.required'] = 'Enter the activity';
         $messages['action.*.required'] = 'Enter the description in this activity';
         $messages['activity_start_time.*.required'] = 'Activity start at';
         $messages['activity_end_time.*.required'] = 'Activity ends at';
      }

      if (gettype($request->program_contact_name) === 'array' && count($request->program_contact_name) > 0) {
         for ($i=0; $i < count($request->program_contact_name); $i++) {
            $rules['program_contact_phone.'.$i] = ['required'];
         }
         $messages['program_contact_phone.*.required'] = 'Enter the phone number';
      }

      $validator = Validator::make($request->all(), $rules, $messages);

      if ($validator->fails()) {
         return response()->json($validator->errors(), 422);;
      }

      // $location = explode(',', $request->event_location);

      // Format longitude (remove the ending bracket)
      // substr($location[1], 0, -1);

      // $lat = (double) ltrim($location[0], $location[0][0]);
      // $long = (double) ltrim($location[1], $location[1][0]);

      // Get location string
      // $event_location = Http::get('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$long.'&key=AIzaSyCisnVFSnc5QVfU2Jm2W3oRLqMDrKwOEoM');
      // $event_location = json_decode($event_location->body());
      // Get location string
      $event_program_location = Http::get('https://maps.googleapis.com/maps/api/geocode/json?place_id='.$request->place_id.'&key='.config('services.maps.partial_key'));

      // $event_location = json_decode($event_location->body());

      $event_program_location_lat = $event_program_location['results'][0]['geometry']['location']['lat'];
      $event_program_location_long = $event_program_location['results'][0]['geometry']['location']['lng'];

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
      $eventProgram->venue_location_lat = $event_program_location_lat;
      $eventProgram->venue_location_long = $event_program_location_long;
      $eventProgram->venue_location = $event_program_location['results'][0]["formatted_address"];
      $eventProgram->direction_description = $request->direction_description;
      if ($request->program_contact_name[0] != null) {
         $contacts = [];
         for ($i=0; $i < count($request->program_contact_name); $i++) {
            array_push($contacts, [$request->program_contact_name[$i] => $request->program_contact_phone[$i]]);
         }
         $eventProgram->contacts = $contacts;
      }

      $eventProgram->save();

      if (gettype($request->activity) === 'array' && count($request->activity) > 0) {
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

      $redirectPath = redirect()->route('client.program.show', $eventProgram)->with('success', "Program added successfully")->getTargetUrl();

      return $request->wantsJson()
                  ? new JsonResponse(['redirectPath' => $redirectPath], 200)
                  : redirect()->route('client.program.show', $eventProgram)->with('success', "Program added successfully");
   }

   public function show(EventProgram $eventProgram)
   {
      $eventProgram->load('eventProgramActivities');
      return view('program.show', compact('eventProgram'));
   }

   public function edit(EventProgram $eventProgram)
   {
      $eventProgram->load('eventProgramActivities');
      $eventTypes = EventTypes::labels();
      return view('program.edit', compact('eventProgram', 'eventTypes'));
   }

   public function update(EventProgram $eventProgram, Request $request)
   {
      $rules = [
         'event_name' => 'required',
         'event_type' => 'required',
         'event_start_date' => 'required',
         'event_start_time' => 'required',
         'event_end_date' => 'required',
         'event_end_time' => 'required',
         'direction_description' => 'required',
      ];

      $messages = [
         'event_name.required' => 'Please enter the event name',
         'event_type.required' => 'Please select the event type',
         'event_start_date.required' => 'Enter the date the event starts',
         'event_start_time.required' => 'Enter the time the event starts',
         'event_end_date.required' => 'Enter the date the event ends',
         'event_end_time.required' => 'Enter the time the event ends',
         'direction_description.required' => 'Enter the directions to the event. E.g. a landmark'
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
         return response()->json($validator->errors(), 422);
      }

      // $location = explode(',', $request->event_location);

      // Format longitude (remove the ending bracket)
      // substr($location[1], 0, -1);

      // $lat = (double) ltrim($location[0], $location[0][0]);
      // $long = (double) ltrim($location[1], $location[1][0]);

      // Get location string
      // $event_location = Http::get('https://maps.googleapis.com/maps/api/geocode/json?latlng='.$lat.','.$long.'&key=AIzaSyCisnVFSnc5QVfU2Jm2W3oRLqMDrKwOEoM');
      // $event_location = json_decode($event_location->body());

      if ($request->has('place_id') && $request->place_id != NULL) {
         $event_program_location = Http::get('https://maps.googleapis.com/maps/api/geocode/json?place_id='.$request->place_id.'&key='.config('services.maps.partial_key'));
         $event_program_location_lat = $event_program_location['results'][0]['geometry']['location']['lat'];
         $event_program_location_long = $event_program_location['results'][0]['geometry']['location']['lng'];
      }

      $eventProgram->update([
         'event_name' => $request->event_name,
         'event_type' => $request->event_type,
         'start_date' => $request->event_start_date.' '.$request->event_start_time,
         'end_date' => $request->event_end_date.' '.$request->event_end_time,
         'venue_location_lat' => $request->has('place_id') && $request->place_id != NULL ? $event_program_location_lat : $eventProgram->venue_location_lat,
         'venue_location_long' => $request->has('place_id') && $request->place_id != NULL ? $event_program_location_long : $eventProgram->venue_location_long,
         'venue_location' => $request->has('place_id') && $request->place_id != NULL ? $event_program_location['results'][0]["formatted_address"] : $eventProgram->venue_location,
         'direction_description' => $request->direction_description,
      ]);

      if ($request->program_contact_name[0] != null) {
         $contacts = [];
         for ($i=0; $i < count($request->program_contact_name); $i++) {
            array_push($contacts, [$request->program_contact_name[$i] => $request->program_contact_phone[$i]]);
         }
         $eventProgram->update([
            'contacts' => $contacts
         ]);
      }

      $programActivities = EventProgramActivity::where('event_program_id', $eventProgram->id)->get();

      foreach ($programActivities as $activity) {
         $activity->delete();
      }

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

      $redirectPath = redirect()->route('client.program.show', $eventProgram->id)->with('success', "Program updated successfully")->getTargetUrl();

      return $request->wantsJson()
                  ? new JsonResponse(['redirectPath' => $redirectPath], 200)
                  : redirect()->route('client.program.show', $eventProgram->id)->with('success', "Program updated successfully");
   }

   public function pay(Request $request)
   {
      $account_reference = Str::upper(Str::random(3)).time().Str::upper(Str::random(3));
      $phone_number = substr($request->phone_number, -9);
      $phone_number = '254'.$phone_number;
      $eventProgram = EventProgram::find($request->program_id);

      $token = Pesapal::accessToken();
      $url = Pesapal::baseUrl().'/api/Transactions/SubmitOrderRequest';
      $notification_id = PesapalNotificationUrl::where('payment_purpose', 'Program Payment')->first()->ipn_id;
      $response = Http::timeout(3)
         ->withToken($token)->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
         ])
         ->post($url, [
            'id' => $account_reference,
            'currency' => 'KES',
            // TODO: Change Amount to be actual amount in prod
            'amount' => 100,
            'description' => 'Payment of Event Program',
            'callback_url' => route('pesapal.program.payment.success.callback'),
            'cancellation_url' => route('pesapal.program.payment.failed.callback'),
            'notification_id' => $notification_id,
            'billing_address' => [
               'phone_number' => auth()->user()->phone_number,
               'email_address' => auth()->user()->email,
               'country_code' => 'KE',
               'first_name' => auth()->user()->first_name,
               'last_name' => auth()->user()->last_name,
            ]
         ]);

      PesapalPayment::create([
         'payable_id' => $eventProgram->id,
         'payable_type' => EventProgram::class,
         'tracking_id' => json_decode($response)->order_tracking_id,
      ]);

      $redirect_url = json_decode($response)->redirect_url;
      return redirect($redirect_url);
   }

   public function pdf(EventProgram $eventProgram)
   {
      if ($eventProgram->event_id != null) {
         $eventProgram->load('event');
      }
      $pdf = PDF::loadView('program.pdf', ['program' => $eventProgram]);

      return $pdf->download($eventProgram->event_name.'.pdf');
   }

   public function share(Request $request)
   {
      $this->validate($request, [
         'emails' => ['required'],
      ]);

      $program = EventProgram::find($request->program_id);
      $program_pdf = PDF::loadView('program.pdf', ['program' => $program]);

      $emails = explode(',', $request->emails);

      foreach($emails as $email) {
         ShareEventProgram::dispatchAfterResponse($email, $program_pdf->output(), $program->event->event_name);
      }

      return back()->with('success', 'Program has been shared');
   }

   public function delete(Request $request)
   {
      EventProgramActivity::where('event_program_id', $request->program_id)->delete();

      EventProgram::destroy($request->program_id);

      session()->put('success', 'Program deleted successfully');

      return redirect()->route('client.programs.index');
   }
}
