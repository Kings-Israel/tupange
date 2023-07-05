<?php

namespace App\Http\Controllers;

use PDF;
use Image;
use Carbon\Carbon;
use App\Models\Gift;
use App\Models\Role;
use App\Models\User;
use App\Models\Event;
use App\Models\Order;
use App\Models\Budget;
use App\Helpers\Pesapal;
use App\Models\Category;
use App\Models\Register;
// use App\Enums\EventTypes;
use App\Models\UserRole;
use App\Models\EventTask;
use App\Models\EventUser;
use App\Models\EventTypes;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\SendEventTicket;
use App\Jobs\SendTaskReminder;
use App\Models\PesapalPayment;
use App\Models\RegisterTicket;
use App\Exceptions\MailNotSent;
use App\Models\EventGuestDetail;
use App\Jobs\SendEventUserInvite;
use App\Models\BudgetTransaction;
use Illuminate\Http\JsonResponse;
use App\Models\EventTicketPayment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\PesapalNotificationUrl;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Notifications\UserEventNotification;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use App\Http\Controllers\MpesaPaymentController;


class EventController extends Controller
{
   // public function __construct()
   // {
   //    $this->middleware(['auth', 'auth.session']);
   // }

   /**
    * View User Events
    *
    */
   public function index()
   {
      return view('events.index');
   }

   /**
    *
    * Show Create Event Form
    *
    */
   public function create()
   {
      // $eventTypes = EventTypes::labels();
      $eventTypes = EventTypes::all();
      $event_type = null;
      $event_start_date = null;
      return view('events.create', compact('eventTypes', 'event_type', 'event_start_date'));
   }

   /**
    *
    * Show Create Event Form with preset parameters
    *
    */
   public function createEvent(Request $request)
   {
      $event_type = $request->event_type != null ? $request->event_type : '';
      $event_start_date = $request->event_date != null ? Carbon::parse($request->event_date)->format('Y-m-d') : '';
      $event_type_custom = $request->event_type_custom != null ? $request->event_type_custom : '';
      $eventTypes = EventTypes::all();
      return view('events.create', compact('eventTypes', 'event_type', 'event_start_date', 'event_type_custom'));
   }

   /**
    *
    * Store Event
    *
    */
   public function store(Request $request)
   {
      $validator = Validator::make($request->all(),
         [
            'event_name'=>'required',
            'event_type' => 'required',
            'event_type_custom' => ['required_if:event_type,Other'],
            'event_start_date' => 'required',
            'event_end_date' => 'required',
            'event_end_time' => 'required',
            'event_end_time' => 'required',
            'event_description' => 'required',
            'event_poster' => 'required|image|mimes:jpeg,png,jpg',
            'corporate_company_name' => ['required_with:corporate_event,corporate_event'],
            'corporate_company_address' => ['required_with:corporate_event,corporate_event']
         ]);

      if ($validator->fails()) {
         return $request->wantsJson()
                  ? new JsonResponse(['errors' => $validator->errors()], 422)
                  : redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
      }

      // If not logged in save data to session
      if (!auth()->check()) {
         $event['event_name'] = request('event_name');
         $event['event_type'] = request('event_type') != 'Other' ? request('event_type') : request('event_type_custom');
         $event['event_description'] = request('event_description');
         $event['event_start_date'] = request('event_start_date').' '.request('event_start_time');
         $event['event_end_date'] = request('event_end_date').' '.request('event_end_time');
         if ($request->has('corporate_event')) {
            $event['isCorporate'] = True;
         } else {
            $event['isCorporate'] = False;
         }

         if ($request->has('corporate_company_name')) {
            $event['corporate_company_name'] = $request->corporate_company_name;
         } else {
            $event['corporate_company_name'] = NULL;
         }

         if ($request->has('corporate_company_address')) {
            $event['corporate_company_address'] = $request->corporate_company_address;
         } else {
            $event['corporate_company_address'] = NULL;
         }

         // Get location string
         $event_location = Http::get('https://maps.googleapis.com/maps/api/geocode/json?place_id='.$request->place_id.'&key=AIzaSyCisnVFSnc5QVfU2Jm2W3oRLqMDrKwOEoM');

         $event_location_lat = $event_location['results'][0]['geometry']['location']['lat'];
         $event['event_location_lat'] = $event_location_lat;

         $event_location_long = $event_location['results'][0]['geometry']['location']['lng'];
         $event['event_location_long'] = $event_location_long;

         $event['event_location'] = $request->event_location ? $request->event_location : $event_location['results'][0]["formatted_address"];

         $settings = [];

         $settings['events_guests_expected'] = $request->event_guests_expected;
         $settings['events_guests_max'] = $request->event_guests_max;

         $event_settings = json_encode($settings);

         $event['event_settings'] = $event_settings;

         if ($request->hasfile('event_poster')) {
            $image = $request->file('event_poster');
            $input['imagename'] = time().'.'.$image->extension();

            $filePath = public_path('storage/event/poster');
            $img = Image::make($image->path());
            $img->resize(700, 464, function($const) {
               $const->aspectRatio();
            })->save($filePath.'/'.$input['imagename']);
            $event_poster = $img->basename;
            $event['event_poster'] = $event_poster;
         } else {
            $event['event_poster'] = NULL;
         }

         if ($request->has('event_custom_message')) {
            $event['custom_message'] = $request->event_custom_message;
         } else {
            $event['custom_message'] = NULL;
         }

         $request->session()->put('event', $event);

         if ($request->has('action') && $request->action === 'search-services') {
            session()->put('search-services', true);
         }

         return $request->wantsJson()
                  ? new JsonResponse(['redirectPath' => '', 'message' => 'Event Saved. Login to continue planning.'], 200)
                  : '';
      }

      $event = new Event();
      $user_id = auth()->user()->id;
      $event->user_id = $user_id;
      $event->event_name = request('event_name');
      $event->event_type = request('event_type') != 'Other' ? request('event_type') : request('event_type_custom');
      $event->event_description = request('event_description');
      $event->event_start_date = request('event_start_date').' '.request('event_start_time');
      $event->event_end_date = request('event_end_date').' '.request('event_end_time');
      if ($request->has('corporate_event')) {
         $event->isCorporate = true;
      }
      if ($request->has('corporate_company_name')) {
      $event->corporate_company_name = $request->corporate_company_name;
      }
      if ($request->has('corporate_company_address')) {
         $event->corporate_company_address = $request->corporate_company_address;
      }

      // Get location string
      $event_location = Http::get('https://maps.googleapis.com/maps/api/geocode/json?place_id='.$request->place_id.'&key=AIzaSyCisnVFSnc5QVfU2Jm2W3oRLqMDrKwOEoM');

      // $event_location = json_decode($event_location->body());

      $event->event_location_lat = $event_location['results'][0]['geometry']['location']['lat'];
      $event->event_location_long = $event_location['results'][0]['geometry']['location']['lng'];

      $event->event_location = $request->event_location;

      $settings = [];

      $settings['events_guests_expected'] = $request->event_guests_expected;
      $settings['events_guests_max'] = $request->event_guests_max;

      $event->event_settings = json_encode($settings);

      if ($request->hasfile('event_poster')) {
         $image = $request->file('event_poster');
         $input['imagename'] = time().'.'.$image->extension();

         $filePath = public_path('storage/event/poster');
         $img = Image::make($image->path());
         $img->resize(700, 464, function($const) {
            $const->aspectRatio();
         })->save($filePath.'/'.$input['imagename']);
         $event->event_poster = $img->basename;
         // $event->event_poster = pathinfo($request->event_poster->store('poster', 'event'), PATHINFO_BASENAME);
      }

      if ($request->has('event_custom_message')) {
         $event->custom_message = $request->event_custom_message;
      }

      if ($event->save()) {
         // return Redirect::route('events.show', $event->id)->with('success', 'Event created successfully!!');
         if ($request->has('action') && $request->action === 'search-services') {
            $redirectPath = redirect()->route('client.services.all')->with('success', 'Event created successfully!!')->getTargetUrl();
         } else {
            $redirectPath = redirect()->route('events.show', $event->id)->with('success', 'Event created successfully!!')->getTargetUrl();
         }
         return $request->wantsJson()
                  ? new JsonResponse(['redirectPath' => $redirectPath], 200)
                  : route('events.show', $event->id)->with('success', 'Event created successfully!!');
      } else {
         $redirectPath = redirect()->back()->with('error','Error occurred while creating event!!')->getTargetUrl();
         return $request->wantsJson()
                  ? new JsonResponse(['redirectPath' => $redirectPath], 200)
                  : redirect()->back()->with('error','Error occurred while creating event!!');
      }
   }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {
      $event = Event::find($id);

      $budget_balance = 0;

      //   Get all expenses(credit)
      $expenses = BudgetTransaction::where('event_id', $id)->where('type', 'Expense')->sum('amount');
      // Get all top ups(debit)
      $top_up = BudgetTransaction::where('event_id', $id)->where('type', 'Top Up')->sum('amount');
      // Calculate the difference
      $budget_balance = $top_up - $expenses;

      $event_registration_sales = Register::where('event_id', $event->id)->sum('amount');

      // Calculate Paid Tickets Amount
      $ticket_paid_amount = 0;
      foreach ($event->event_guests as $guest) {
         if ($guest->is_paid) {
            $ticket_paid_amount += (double) $guest->ticket_price * (int) $guest->guests;
         }
      }

      return view('events.show', compact('event', 'budget_balance', 'event_registration_sales', 'ticket_paid_amount'));
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
      $event = Event::find($id);
      $eventTypes = EventTypes::all()->pluck('name');
      $start_time = explode(' ', $event->event_start_date);
      $end_time = explode(' ', $event->event_end_date);
      $event->start_date = $start_time[0];
      $event->start_time = $start_time[1];
      $event->end_date = $end_time[0];
      $event->end_time = $end_time[1];
      $event->settings = json_decode($event->event_settings);

      return view('events.edit', compact('event', 'eventTypes'));
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $id)
   {
      $validator = Validator::make($request->all(),
         [
            'event_name'=>'required',
            'event_type' => 'required',
            'custom_event_type' => ['required_if:event_type,Other'],
            'event_start_date' => 'required',
            'event_end_date' => 'required',
            'event_end_time' => 'required',
            'event_end_time' => 'required',
            'event_description' => 'required',
            'corporate_company_name' => ['required_with:corporate_event,corporate_event'],
            'corporate_company_address' => ['required_with:corporate_event,corporate_event']
         ]);

      if ($validator->fails()) {
         return $request->wantsJson()
                  ? new JsonResponse(['errors' => $validator->errors()], 422)
                  : redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
      }

         $event=Event::find($id);
         $event->event_name=request('event_name');
         $event->event_type=request('event_type') != 'Other' ? request('event_type') : request('custom_event_type');
         $event->event_description=request('event_description');
         $event->event_start_date=request('event_start_date').' '.request('event_start_time');
         $event->event_end_date=request('event_end_date').' '.request('event_end_time');
         if ($request->has('corporate_event')) {
         $event->isCorporate = true;
      }
      if ($request->has('corporate_company_name')) {
         $event->corporate_company_name = $request->corporate_company_name;
      }
      if ($request->has('corporate_company_address')) {
         $event->corporate_company_address = $request->corporate_company_address;
      }

      if ($request->has('place_id') && $request->place_id != NULL) {
         // Get location string
         $event_location = Http::get('https://maps.googleapis.com/maps/api/geocode/json?place_id='.$request->place_id.'&key=AIzaSyCisnVFSnc5QVfU2Jm2W3oRLqMDrKwOEoM');
         // $event_location = json_decode($event_location->body());


         $event->event_location_lat = $event_location['results'][0]['geometry']['location']['lat'];
         $event->event_location_long = $event_location['results'][0]['geometry']['location']['lng'];

         $event->event_location = $request->event_location;
      }

      $settings = [];

      $settings['events_guests_expected'] = $request->event_guests_expected;
      $settings['events_guests_max'] = $request->event_guests_max;


      $event->event_settings = json_encode($settings);

      if ($request->hasfile('event_poster')) {
         Storage::disk('event')->delete('poster/'.$event->event_poster);

         $image = $request->file('event_poster');
         $input['imagename'] = time().'.'.$image->extension();

         $filePath = public_path('storage/event/poster');
         $img = Image::make($image->path());
         $img->resize(700, 464, function($const) {
            $const->aspectRatio();
         })->save($filePath.'/'.$input['imagename']);
         $event->event_poster = $img->basename;
      }

      if ($event->save()) {
         $redirectPath = redirect()->route('events.show', $event->id)->with('success', 'Event updated successfully!!')->getTargetUrl();
         return $request->wantsJson()
                  ? new JsonResponse(['redirectPath' => $redirectPath], 200)
                  : route('events.show', $event->id)->with('success', 'Event updated successfully!!');
      } else {
         $redirectPath = redirect()->back()->with('error','Error occurred while updating event!!')->getTargetUrl();
         return $request->wantsJson()
                  ? new JsonResponse(['redirectPath' => $redirectPath], 200)
                  : redirect()->back()->with('error','Error occurred while updating event!!');
      }
   }

   /**
    *
    * View Event Tasks
    *
    */
   public function eventTasks(Event $event)
   {
      $categories = Category::all();
      return view('events.tasks', compact('event', 'categories'));
   }

   /**
    *
    * Store Event Task
    *
    */
   public function addEventTask(Request $request)
   {
      $rules = [
         'task_name.*' => 'required',
         'date_due.*' => 'required|date'
      ];

      $messages = [
         'task_name.*.required' => 'Please enter the task name'
      ];

      Validator::make($request->all(), $rules, $messages)->validate();

      collect($request->task_name)->each(function ($task, $key) use ($request) {
         EventTask::create([
            'event_id' => $request->event_id,
            'task' => $task,
            'person_responsible' => $request->person_responsible[$key],
            'task_category' => $request->task_category[$key],
            'notify_due' => $request->notify_due[$key],
            'date_due' => $request->date_due[$key],
            'status' => $request->status[$key]
         ]);
      });

      return back()->with('success', 'Tasks is added');
   }

   /**
    *
    * Update Event Task
    *
    */
   public function editEventTask(Request $request)
   {
      $rules = [
         'task_name' => 'required',
         'date_due' => 'required|date'
      ];

      $messages = [
         'task_name.required' => 'Please enter the task name'
      ];

      Validator::make($request->all(), $rules, $messages)->validate();

      $event_task = EventTask::find($request->task_id);
      $event_task->task = $request->task_name;
      $event_task->person_responsible = $request->person_responsible;
      $event_task->task_category = $request->task_category;
      $event_task->notify_due = $request->notify_due;
      $event_task->date_due = $request->date_due;
      $event_task->status = $request->status;

      if ($event_task->save()) {
         return back()->with('success', 'Task updated');
      }

      return back()->with('error', 'Error updating task');
   }

   /**
    *
    * View Event Orders
    *
    */
   public function eventOrders(Event $event)
   {
      return view('events.event_orders', compact('event'));
   }

   /**
    *
    * Delete Event Order
    *
    */
   public function deleteEventOrder(Request $request)
   {
      Order::destroy($request->order_id);

      return back()->with('success', 'Order Deleted');
   }

   public function eventGifts(Event $event)
   {
      return view('events.gifts', compact('event'));
   }

   public function addEventGift(Request $request, Event $event)
   {
      $rules = [
         'title' => ['required', 'string']
      ];

      $messages = [
         'title.required' => 'Please enter the git title'
      ];

      Validator::make($request->all(), $rules, $messages)->validate();

      $event->gifts()->create([
         'title' => $request->title,
         'description' => $request->description,
         'value' => $request->value,
         'received_date' => $request->received_date,
         'received_by' => $request->received_by,
         'received_from' => $request->received_from,
         'phone' => $request->phone,
         'email' => $request->email,
         'image' => $request->hasFile('image') ? pathinfo($request->image->store('gift', 'event'), PATHINFO_BASENAME) : null
      ]);

      return back()->with('success', 'Gift added to registry');
   }

   public function updateEventGift(Request $request, Gift $gift)
   {
      $rules = [
         'title' => ['required', 'string']
      ];

      $messages = [
         'title.required' => 'Please enter the git title'
      ];

      Validator::make($request->all(), $rules, $messages)->validate();

      $gift->update([
         'title' => $request->title,
         'description' => $request->description,
         'value' => $request->value,
         'received_date' => $request->received_date,
         'received_by' => $request->received_by,
         'received_from' => $request->received_from,
         'phone' => $request->phone,
         'email' => $request->email,
      ]);

      if ($request->hasFile('update_image')) {
         $gift->image = pathinfo($request->update_image->store('gift', 'event'), PATHINFO_BASENAME);
      }

      return back()->with('success', 'Gift update');
   }

   /**
    *
    * View Event Guest List
    *
    */
   public function eventGuestList(Event $event)
   {
      return view('events.guest-list', compact('event'));
   }

   /**
    *
    * Import Guest List from Excel File
    */
   public function importGuestList(Request $request, Event $event)
   {
      $this->validate($request, [
         'uploaded_file' => ['required', 'file', 'mimes:xls,xlsx']
      ]);

      $file = $request->file('uploaded_file');

      try {
         $spreadsheet = IOFactory::load($file->getRealPath());
         $sheet = $spreadsheet->getActiveSheet();
         $row_limit = $sheet->getHighestDataRow();
         $column_limit = $sheet->getHighestDataColumn();
         $row_range = range(2, $row_limit);
         $startcount = 2;

         $data = array();

         foreach($row_range as $row) {
            if ($sheet->getCell('C' . $row)->getValue() != '' && $sheet->getCell('D' . $row)->getValue() != '') {
               $guest = EventGuestDetail::create([
                  'event_id' => $request->event_id,
                  'barcode' => Str::upper(Str::random(6)),
                  'status' => $sheet->getCell('B' . $row)->getValue(),
                  'first_name' => $sheet->getCell('C' . $row)->getValue(),
                  'last_name' => $sheet->getCell('D' . $row)->getValue(),
                  'type' => $sheet->getCell('E' . $row)->getValue(),
                  'phone_number' => $sheet->getCell('F' . $row)->getValue(),
                  'email' => $sheet->getCell('G' . $row)->getValue(),
                  'company' => $sheet->getCell('H' . $row)->getValue(),
                  'address' => $sheet->getCell('I' . $row)->getValue(),
                  'diet_instructions' => $sheet->getCell('J' . $row)->getValue(),
                  'table_number' => $sheet->getCell('K' . $row)->getValue(),
                  'invitation_phone_number' => $sheet->getCell('L' . $row)->getValue() ? $sheet->getCell('L' . $row)->getValue() : $sheet->getCell('F' . $row)->getValue(),
                  'invitation_email' => $sheet->getCell('M' . $row)->getValue() ? $sheet->getCell('M' . $row)->getValue() : $sheet->getCell('G' . $row)->getValue(),
                  'custom_question' => $sheet->getCell('N' . $row)->getValue(),
                  'custom_answer' => $sheet->getCell('O' . $row)->getValue(),
                  'created_at' => now(),
                  'ticket_title' => ($sheet->getCell('Q'. $row)->getValue() !== '' && $sheet->getCell('Q'. $row)->getValue() != NULL) && ($sheet->getCell('R'. $row)->getValue() !== '' && $sheet->getCell('R'. $row)->getValue() != NULL) ? $sheet->getCell('Q'. $row)->getValue() : NULL,
                  'ticket_price' => ($sheet->getCell('Q'. $row)->getValue() !== '' && $sheet->getCell('Q'. $row)->getValue() != NULL) && ($sheet->getCell('R'. $row)->getValue() !== '' && $sheet->getCell('R'. $row)->getValue() != NULL) ? $sheet->getCell('R'. $row)->getValue() : NULL,
                  'is_paid' => ($sheet->getCell('R'. $row)->getValue() !== '' && $sheet->getCell('R'. $row)->getValue() != NULL && (int) $sheet->getCell('R'. $row)->getValue() <= 0) ? true : false,
               ]);

               if ($sheet->getCell('P' . $row)->getValue() !== '' && $sheet->getCell('P' . $row)->getValue() != NULL) {
                  $eventUser = EventUser::create([
                     'names' => $sheet->getCell('C' . $row)->getValue().' '.$sheet->getCell('D' . $row)->getValue(),
                     'email' => $sheet->getCell('G' . $row)->getValue(),
                     'event_id' => $request->event_id,
                     'role' => $sheet->getCell('P' . $row)->getValue(),
                  ]);
                  $user = User::where('email', $sheet->getCell('G' . $row)->getValue())->first();
                  $event = Event::where('id', $request->event_id)->with('user')->first();
                  $role = Role::where('name', ucfirst($sheet->getCell('P'. $row)->getValue()))->first();
                  if ($user) {
                     $eventUser->update([
                        'user_id' => $user->id
                     ]);

                     UserRole::create([
                        'user_id' => $user->id,
                        'event_id' => $request->event_id,
                        'role_id' => $role->id
                     ]);

                     $user->notify(new UserEventNotification($event->id, $event->event_name, $event->user->f_name.' '.$event->user->l_name, $role->name));
                  }
                  $data = [
                     'email' => $eventUser->email,
                     'subject' => 'Invitation to collaborate in Event Management',
                     'message' => 'This is an invitation sent by '. $event->user->f_name.' '.$event->user->l_name .' to you for assistance in the management of the event '. $event->event_name . ' in the role '. $role->name .'. Click on the button below to login and access the details.',
                     'event_id' => $event->id,
                     'role_id' => $role->id,
                  ];

                  SendEventUserInvite::dispatchAfterResponse($data);
               }

               // Add Event Ticket if included
               if(($sheet->getCell('Q'. $row)->getValue() !== '' && $sheet->getCell('Q'. $row)->getValue() != NULL) && ($sheet->getCell('R'. $row)->getValue() !== '' && $sheet->getCell('R'. $row)->getValue() != NULL)) {
                  // Check if Ticket is already uploaded
                  $ticket = RegisterTicket::where('title', $sheet->getCell('Q'. $row)->getValue())->where('price', $sheet->getCell('R'. $row)->getValue())->first();

                  // Add ticket if not uploaded
                  if(!$ticket) {
                     RegisterTicket::create([
                        'event_id' => $request->event_id,
                        'title' => $sheet->getCell('Q'. $row)->getValue(),
                        'description' => $sheet->getCell('Q'. $row)->getValue() !== '' && $sheet->getCell('Q'. $row)->getValue() != NULL ? $sheet->getCell('Q'. $row)->getValue() : NULL,
                        'price' => $sheet->getCell('R'. $row)->getValue(),
                        'guest_limit' => $sheet->getCell('S'. $row)->getValue() !== '' && $sheet->getCell('S'. $row)->getValue() != NULL && (int) $sheet->getCell('S'. $row)->getValue() > 0 ? $sheet->getCell('S'. $row)->getValue() : 1,
                     ]);
                  }
               }

               // Send Event Ticket
               if($sheet->getCell('G' . $row)->getValue() !== '' && $sheet->getCell('G' . $row)->getValue() != NULL) {
                  $data = [];
                  $data['email'] = $sheet->getCell('G' . $row)->getValue();
                  $data['guest_name'] = $sheet->getCell('C' . $row)->getValue() . ' ' . $sheet->getCell('D' . $row)->getValue();
                  $data['subject'] = $event->event_name . ' ticket.';
                  $data['content'] = 'You have been invited for the event, '.$event->event_name.'.<br /> Your ticket for the event has been attached to this email.<br /> Scan the QR Codes on the tickets to either to view the event details or to view the location of the event.';

                  $pdf = PDF::loadView('partials.event-ticket-pdf', ['guest' => $guest, 'map' => true]);

                  SendEventTicket::dispatchAfterResponse($data['email'], $data['subject'], $data['content'], $pdf->output());

                  $guest->update([
                     'ticketSent' => true
                  ]);
               }
            }

            $startcount++;
         }

         // DB::table('event_guest_details')->insert($data);
      } catch (Exception $e) {
         session()->put('error', 'Error uploading guest list');
         return redirect()->back()->with('error', "Error adding guest list");
      }

      session()->put('success', "Guests added successfully");
      return redirect()->back();
   }

   /**
    *
    * Download Guest List to Excel File
    */
   public function downloadEventGuestList($customer_data)
   {
      ini_set('max_execution_time', 0);
      ini_set('memory_limit', '4000M');
      try {
          $spreadSheet = new Spreadsheet();
          $spreadSheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);
          $spreadSheet->getActiveSheet()->fromArray($customer_data);
          $Excel_writer = new Xls($spreadSheet);
          header('Content-Type: application/vnd.ms-excel');
          header('Content-Disposition: attachment;filename="Guest_List.xls"');
          header('Cache-Control: max-age=0');
          ob_end_clean();
          $Excel_writer->save('php://output');
          exit();
          return back()->with('success', 'File downloaded');
      } catch (Exception $e) {
          return;
      }
   }

   /**
    *
    * Export Event Guest Lists
    *
    */
   public function exportGuestList(Event $event)
   {
      $data = $event->event_guests;

      // Add Role to data if guest was assigned role
      collect($data)->each(function($guest) {
         $guestRole = EventUser::where('email', $guest->email)->first();
         $guest['role'] = $guestRole;
      });

      $data_array [] = array("Barcode","Status","First Name","Last Name","Type","Phone Number","Email","Company","Address","Diet Instructions","Table Number","Invitation Phone Number","Invitation Email","Custom Question","Custom Answer","Role");

      foreach($data as $data_item)
      {
          $data_array[] = array(
              'Barcode' =>$data_item->barcode,
              'Status' => $data_item->status,
              'First Name' => $data_item->first_name,
              'Last Name' => $data_item->last_name,
              'Type' => $data_item->type,
              'phone_number' =>$data_item->phone_number,
              'Email' => $data_item->email,
              'Company' => $data_item->company,
              'Address' => $data_item->address,
              'Diet Instructions' => $data_item->diet_instructions,
              'Table Number' => $data_item->table_number,
              'Invitation phone_number' => $data_item->invitation_phone_number,
              'Invitation email' => $data_item->invitation_email,
              'Custom Question' => $data_item->custom_question,
              'Custom Answer' => $data_item->custom_answer,
              'Role' => $data_item->role ? $data_item->role->role : ''
          );
      }
      $this->downloadEventGuestList($data_array);
   }

   /**
    *
    * Show Add Event Guest Form
    *
    */
   public function addGuestForm($id)
   {
      $event = Event::find($id);

      $tickets = RegisterTicket::where('event_id', $event->id)->get();

      return view('events.add-guest')->with(['event' => $event, 'tickets' => $tickets]);
   }

   /**
    *
    * Add Event Guest
    *
    */
   public function addEventGuest(Request $request, Event $event)
   {
      $rules = [
         'first_name'=> 'required',
         'last_name' => 'required',
      ];

      if ($request->role != null) {
         $rules['email'] = ['required'];
      }

      if ($request->extend_invitation == "extend_invitation") {
         $rules['invited_guests_number'] = 'required';
      }

      $validator = Validator::make($request->all(), $rules);
      if ($validator->fails()) {
         return response()->json($validator->errors(), 422);
      }

      $barcode = Str::upper(Str::random(8));
      $registers = EventGuestDetail::all()->pluck('barcode');

      do {
         $barcode = Str::upper(Str::random(8));
      } while (in_array($barcode, $registers->toArray()));

      $guest = $event->event_guests()->create([
         'barcode' => $barcode,
         'status' => $request->status,
         'first_name' => $request->first_name,
         'last_name' => $request->last_name,
         'type' => $request->custom_category === 'custom_category' ? $request->custom_type : $request->type,
         'phone_number' => $request->phone_number,
         'email' => $request->email,
         'address' => $request->address,
         'company' => $request->company,
         'diet_instructions' => $request->diet_instructions,
         'table_number' => $request->table_number,
         'extend_invitation' => $request->extend_invitation ? 1 : 0,
         'invited_guests_number' => $request->invited_guests_number,
         'invitation_email' => $request->invitation_email,
         'invitation_phone_number' => $request->invitation_phone_number,
         'custom_question' => $request->custom_message,
         'custom_answer' => $request->custom_answer,
         'ticket_title' => $request->ticket_title,
         'ticket_price' => $request->ticket_price == null ? 0 : $request->ticket_price,
         'guests' => $request->guests,
         'notes' => $request->notes,
         'is_paid' => $request->ticket_price != null && (int) $request->ticket_price <= 0 ? true : false,
      ]);

      if ($request->role != null) {
         $role = Role::where('name', $request->role)->first();

         $eventUser = EventUser::create([
            'names' => $request->first_name.' '.$request->last_name,
            'email' => $request->email,
            'event_id' => $event->id,
            'role' => $role->name,
         ]);

         $data = [
            'email' => $eventUser->email,
            'subject' => 'Invitation to collaborate in Event Management',
            'message' => 'This is an invitation sent by '. $event->user->f_name.' '.$event->user->l_name .' to you for assistance in the management of the event '. $event->event_name . ' in the role '. $role->name .'. Click on the button below to login and access the details.',
            'event_id' => $event->id,
            'role_id' => $role->id,
         ];

         SendEventUserInvite::dispatchAfterResponse($data);

         $eventUser->isSent = 1;
         $eventUser->save();

         $user = User::where('email', $request->email)->first();
         if ($user) {
            $eventUser->update([
               'user_id' => $user->id
            ]);

            UserRole::create([
               'user_id' => $user->id,
               'event_id' => $request->event_id,
               'role_id' => $role->id
            ]);

            $user->notify(new UserEventNotification($event->id, $event->event_name, $event->user->f_name.' '.$event->user->l_name, $role->name));
         }
      }

      if ($guest->email != null) {
         $data['email'] = $guest->email;
         $data['guest_name'] = $guest->first_name . ' ' . $guest->last_name;
         $data['subject'] = $guest->event->event_name . ' ticket.';
         $data['content'] = 'You have been invited for the event, '.$guest->event->event_name.'.<br /> Your ticket for the event has been attached to this email.<br /> Scan the QR Codes on the tickets to either to view the event details or to view the location of the event.';

         $pdf = PDF::loadView('partials.event-ticket-pdf', ['guest' => $guest, 'map' => true]);

         SendEventTicket::dispatchAfterResponse($data['email'], $data['subject'], $data['content'], $pdf->output());

         $guest->update([
            'ticketSent' => true
         ]);
      }

      session()->put('success', 'Guest added successfully');

      $redirectPath = redirect()->route('client.event.guests', $event)->getTargetUrl();

      return $request->wantsJson()
                  ? new JsonResponse(['redirectPath' => $redirectPath], 200)
                  : redirect()->back();
   }

   /**
    *
    * Show Edit Event Guest Form
    *
    */
   public function editGuestForm($event_id, $guest_id)
   {
      $guest = EventGuestDetail::find($guest_id);

      $tickets = RegisterTicket::where('event_id', $event_id)->get();

      return view('events.edit-guest')->with(['guest' => $guest, 'tickets' => $tickets]);
   }

   /**
    *
    * Store Edited Guest Details
    *
    */
   public function editEventGuest(Request $request)
   {
      $rules = [
         'first_name'=>'required',
         'last_name' => 'required',
      ];

      $validator = Validator::make($request->all(), $rules);
      if ($validator->fails()) {
         return Redirect::back()->withErrors($validator)->withInput()->with('error', 'Please fill all the required fields');
      }

      $eventGuest = EventGuestDetail::find($request->guest_id);

      $eventGuest->update([
         'status' => $request->status,
         'first_name' => $request->first_name,
         'last_name' => $request->last_name,
         'type' => $request->has('custom_category') ? $request->custom_type : $request->type,
         'phone_number' => $request->phone_number,
         'email' => $request->email,
         'address' => $request->address,
         'diet_instructions' => $request->diet_instructions,
         'table_number' => $request->table_number,
         'invitation_email' => $request->invitation_email,
         'invitation_phone_number' => $request->invitation_phone_number,
         'invitation_custom_message' => $request->invitation_custom_message,
         'custom_question' => $request->custom_message,
         'custom_answer' => $request->custom_answer,
         'ticket_title' => $request->ticket_title,
         'ticket_price' => $request->ticket_price == null ? 0 : $request->ticket_price,
         'guests' => $request->guests,
         'notes' => $request->notes,
      ]);

      if ($request->role != null) {
         $eventUser = EventUser::where('email', $request->email)->first();
         if ($eventUser) {
            $eventUser->update([
               'names' => $request->first_name.' '.$request->last_name,
               'email' => $request->email,
               'role' => $request->role,
            ]);
         } else {
            $eventUser = EventUser::create([
               'names' => $request->first_name.' '.$request->last_name,
               'email' => $request->email,
               'role' => $request->role,
               'event_id' => $request->event_id
            ]);
         }

         $user = User::where('email', $request->email)->first();
         if ($user) {
            $event = Event::where('id', $request->event_id)->with('user')->first();
            $role = Role::where('name', $request->role)->first();
            $eventUser->update([
               'user_id' => $user->id
            ]);

            UserRole::updateOrCreate(
               ['user_id' => $user->id, 'event_id' => $request->event_id],
               ['role_id' => $role->id]
            );

            $user->notify(new UserEventNotification($event->id, $event->event_name, $event->user->f_name.' '.$event->user->l_name, $role->name));
         }
      }

      if ($eventGuest->email != null) {
         $data['email'] = $eventGuest->email;
         $data['guest_name'] = $eventGuest->first_name . ' ' . $eventGuest->last_name;
         $data['subject'] = $eventGuest->event->event_name . ' ticket.';
         $data['content'] = 'Your ticket for the event ' . $eventGuest->event->event_name . ' has been attached to this email.';

         $pdf = PDF::loadView('partials.event-guest-ticket-pdf', ['guest' => $eventGuest]);

         SendEventTicket::dispatchAfterResponse($data['email'], $data['subject'], $data['content'], $pdf->output());

         $eventGuest->update([
            'ticketSent' => true
         ]);
      }

      // return back()->with('success', 'Guest updated successfully');
      $redirectPath = redirect()->route('client.event.guests', $eventGuest->event_id)->with('success', "Guest updated successfully")->getTargetUrl();

      return $request->wantsJson()
                  ? new JsonResponse(['redirectPath' => $redirectPath], 200)
                  : redirect()->route('client.event.guests', $eventGuest->event_id)->with('success', "Guest updates successfully");
   }

   /**
    *
    * Send Event Invite To Selected Guests
    *
    */
   public function sendInviteToGuests(Request $request)
   {
      foreach ($request->select_guest as $key => $value) {
         $guest = EventGuestDetail::find($value);

         if($guest->email != NULL) {
            $data['email'] = $guest->email;
            $data['guest_name'] = $guest->first_name . ' ' . $guest->last_name;
            $data['subject'] = $guest->event->event_name . ' ticket.';
            $data['content'] = 'You have been invited for the event, '.$guest->event->event_name.'.<br /> Your ticket for the event has been attached to this email.<br /> Scan the QR Codes on the tickets to either to view the event details or to view the location of the event.';

            $pdf = PDF::loadView('partials.event-ticket-pdf', ['guest' => $guest, 'map' => true]);

            SendEventTicket::dispatchAfterResponse($data['email'], $data['subject'], $data['content'], $pdf->output());
         }
      }

      session()->put('success', 'Emails Sent');

      return back();
   }

   public function markGuestsAsAttended(Request $request)
   {
      dd($request->all());
   }

   /**
    *
    * View Event Budget Dashboard
    *
    */
   public function eventBudget(Event $event)
   {
      $initial = Budget::where('event_id', $event->id)->where('title', 'Initial Budget')->first();
      $initial_budget = collect([]);
      if ($initial) {
         $initial_budget = BudgetTransaction::where('event_id', $event->id)->where('budget_id', $initial->id)->first();
      }
      $budgets = Budget::where('event_id', $event->id)->where('title', '!=', 'Initial Budget')->get();
      foreach ($budgets as $budget) {
         $budget->balance = 0;
         //   Get all expenses(credit)
         $expenses = BudgetTransaction::where(['event_id' => $event->id, 'budget_id' => $budget->id])->where('type', 'Expense')->sum('amount');
         // Get all top ups(debit) apart from initial top up
         $top_up = BudgetTransaction::where(['event_id' => $event->id, 'budget_id' => $budget->id])->where('title', '!=', 'Initial Budget for the event')->where('type', 'Top Up')->sum('amount');
         // Calculate the difference
         $budget->balance = $top_up - $expenses;

      }

      // Calucate for chart
      $chartData = [];
      // Get categories
      $categories = BudgetTransaction::where('transaction_service_category', '!=', NULL)->where('event_id', $event->id)->get()->pluck('transaction_service_category');

      foreach(collect($categories)->unique() as $category) {
         $sum = BudgetTransaction::where('transaction_service_category', $category)->where('event_id', $event->id)->sum('amount');
         $chartData[] = [
            'name' => $category,
            'amount' => $sum
         ];
      }

      return view('events.budget', compact('event', 'budgets', 'initial', 'initial_budget', 'chartData'));
   }

   /**
    *
    * Store Event Budget
    *
    */
   public function eventAddBudget(Request $request)
   {
      $rules = [
         'title' => 'required'
      ];

      Validator::make($request->all(), $rules)->validate();

      $budget = Budget::create($request->all());

      return redirect()->route('client.event.budget.transactions', ['event' => $request->event_id, 'budget' => $budget->id])->with('success', 'Budget Added');
   }

   /**
    *
    * Show Event Budget Transactions
    *
    */
   public function eventBudgetTransactions(Event $event, Budget $budget)
   {
      return view('events.budget-transactions', compact('event', 'budget'));
   }

   /**
    *
    * Store Event Initial Budget
    *
    */
   public function addInitialBudget(Request $request)
   {
      $budget = Budget::create([
         'event_id' => $request->event_id,
         'title' => 'Initial Budget',
         'description' => $request->description
      ]);

      BudgetTransaction::create([
         'budget_id' => $budget->id,
         'event_id' => $request->event_id,
         'type' => 'Top Up',
         'title' => 'Initial Budget for the event',
         'description' => $request->amount.' is the initial budget for the event',
         'amount' => $request->amount,
         'date' => now(),
      ]);

      return redirect()->route('client.event.budget.transactions', ['event' => $request->event_id, 'budget' => $budget->id])->with('success', 'Budget Added');
   }

   /**
    *
    * Show Event Add Budget Transaction Form
    *
    */
   public function showAddBudgetTransaction(Event $event, Budget $budget)
   {
      // Get categories
      $categories = Category::all()->pluck('name');

      return view('events.add-transaction', compact('event', 'budget', 'categories'));
   }

   /**
    *
    * Store Event Budget Transaction
    *
    */
   public function addEventBudgetTransaction(Request $request)
   {
      BudgetTransaction::create($request->all());

      $event = Event::find($request->event_id);
      $budget = Budget::find($request->budget_id);

      return redirect()->route('client.event.budget.transactions', ['event' => $event, 'budget' => $budget])->with(['success' => 'Transaction added']);
   }

   /**
    *
    * Update Event Budget Transaction
    *
    */
   public function editEventBudgetTransaction(Request $request)
   {
      $transaction = BudgetTransaction::find($request->transaction_id);
      $transaction->update([
         'date' => $request->date,
         'type' => $request->type,
         'title' => $request->title,
         'description' => $request->description,
         'amount' => $request->amount,
         'reference' => $request->reference,
         'transaction_service_category' => $request->transaction_service_category
      ]);

      return back()->with('success', 'Transaction updated');
   }

   /**
    *
    * Delete Event Budget Transaction
    *
    */
   public function deleteEventBudgetTransaction(Request $request)
   {
      BudgetTransaction::destroy($request->transaction_id);

      return back()->with('success', 'Transaction deleted');
   }

   /**
    *
    * Update Event Budget
    *
    */
   public function editEventBudget(Request $request)
   {
      $budget = Budget::find($request->budget_id);
      $budget->update([
         'title' => $request->title,
         'description' => $request->description
      ]);

      return back()->with('success', 'Budget updated');
   }

   /**
    *
    * Delete Event Budget
    *
    */
   public function deleteEventBudget(Request $request)
   {
      BudgetTransaction::where('budget_id', $request->budget_id)->delete();

      Budget::destroy($request->budget_id);

      return back()->with('success', 'Budget deleted');
   }

   /**
    *
    * Show Event Registration Tickets
    *
    */
   public function eventRegistration(Event $event)
   {
      $tickets = RegisterTicket::where('event_id', $event->id)->get();

      return view('events.event-registration', compact('event', 'tickets'));
   }

   public function eventTickets(Event $event)
   {
      $i = 1;
      $tickets = RegisterTicket::where('event_id', $event->id)->get();

      return view('events.manage-tickets', compact('event', 'tickets', 'i'));
   }

   /**
    *
    * Store Event Ticket
    *
    */
   public function addEventTicket(Request $request)
   {
      RegisterTicket::create([
         'event_id' => $request->event_id,
         'title' => $request->title,
         'description' => $request->description,
         'price' => $request->price,
         'guest_limit' => $request->guest_limit
      ]);

      return back()->with('success', 'Ticket added');
   }

   /**
    *
    * Update Event Ticket
    *
    */
   public function editEventTicket(Request $request)
   {
      $ticket = RegisterTicket::find($request->ticket_id);
      $ticket->update([
         'title' => $request->title,
         'description' => $request->description,
         'price' => $request->price,
         'guest_limit' => $request->guest_limit
      ]);

      return back()->with('success', 'Ticket details updated');
   }

   /**
    *
    * Delete Event Ticket
    *
    */
   public function deleteEventTicket($id)
   {
      RegisterTicket::destroy($id);

      return back()->with('success', 'Ticket deleted');
   }

   public function eventRegisterGuest(Request $request)
   {
      $barcode = Str::upper(Str::random(8));
      $registers = Register::all()->pluck('barcode');

      do {
         $barcode = Str::upper(Str::random(8));
      } while (in_array($barcode, $registers->toArray()));

      Register::create([
         'barcode' => $barcode,
         'names' => $request->names,
         'phone_number' => $request->phone_number,
         'email' => $request->email,
         'address' => $request->address,
         'company' => $request->company,
         'ticket_title' => $request->ticket_title,
         'ticket_price' => $request->ticket_price == null ? 0 : $request->ticket_price,
         'guests' => $request->guests,
         'amount' => $request->amount,
         'notes' => $request->notes,
         'event_id' => $request->event_id,
      ]);

      return back()->with('success', 'Guest registered successfully');
   }

   public function eventEditRegisterGuest(Request $request)
   {
      $guest = Register::find($request->guest_id);
      $guest->update([
         'names' => $request->names,
         'phone_number' => $request->phone_number,
         'email' => $request->email,
         'address' => $request->address,
         'company' => $request->company,
         'ticket_title' => $request->ticket_title,
         'ticket_price' => $request->ticket_price,
         'guests' => $request->guests,
         'amount' => $request->amount,
         'notes' => $request->notes,
      ]);

      return back()->with('success', 'Guest details updated');
   }

   /**
    *
    * Download Event Guest Ticket
    *
    */
   public function downloadEventTicket($id)
   {
      $guest = EventGuestDetail::find($id);
      $pdf = PDF::loadView('partials.event-ticket-pdf', ['guest' => $guest]);
      return $pdf->download($guest->first_name.' '.$guest->last_name.'.pdf');
   }

   /**
    *
    * Send Event Guest ticket to guest
    *
    */
   public function sendEventTicket($id)
   {
      $guest = EventGuestDetail::find($id);
      if ($guest->email != null) {
         $data['email'] = $guest->email;
         $data['guest_name'] = $guest->first_name.' '.$guest->last_name;
         $data['subject'] = $guest->event->event_name.' ticket.';
         $data['content'] = 'You have been invited for the event, '.$guest->event->event_name.'.<br /> Your ticket for the event has been attached to this email.<br /> Scan the QR Codes on the tickets to either to view the event details or to view the location of the event.';

         $pdf = PDF::loadView('partials.event-ticket-pdf', ['guest' => $guest, 'map' => false]);

         SendEventTicket::dispatchAfterResponse($data['email'], $data['subject'], $data['content'], $pdf->output());

         session()->put('success', 'Ticket Sent');

         return back();
      } else {
         session()->put('error', 'The guest does not have an email address');

         return back();
      }
   }

   /**
    *
    * Send Event Guest ticket to guest with map
    *
    */
   public function sendEventTicketWithMap($id)
   {
      $guest = EventGuestDetail::find($id);
      if ($guest->email != null) {
         $data['email'] = $guest->email;
         $data['guest_name'] = $guest->first_name.' '.$guest->last_name;
         $data['subject'] = $guest->event->event_name.' ticket.';
         $data['content'] = 'You have been invited for the event, '.$guest->event->event_name.'.<br /> Your ticket for the event has been attached to this email.<br /> Scan the QR Codes on the tickets to either to view the event details or to view the location of the event.';

         $pdf = PDF::loadView('partials.event-ticket-pdf', ['guest' => $guest, 'map' => true]);

         SendEventTicket::dispatchAfterResponse($data['email'], $data['subject'], $data['content'], $pdf->output());

         session()->put('success', 'Ticket Sent');

         return back();
      } else {
         session()->put('error', 'The guest does not have an email address');

         return back();
      }
   }

   /**
    *
    * View Event Users List
    *
    */
   public function eventUsersList(Event $event)
   {
      return view('events.users', compact('event'));
   }

   /**
    *
    * Store Event User
    *
    */
   public function addEventUser(Request $request)
   {
      $eventUser = EventUser::create($request->all());

      $event = Event::where('id', $request->event_id)->with('user')->first();
      $role = Role::where('name', $request->role)->first();

      $user = User::where('email', $request->email)->first();
      if ($user) {
         $eventUser->update([
            'user_id' => $user->id
         ]);

         UserRole::create([
            'user_id' => $user->id,
            'event_id' => $request->event_id,
            'role_id' => $role->id
         ]);

         $user->notify(new UserEventNotification($event->id, $event->event_name, $event->user->f_name.' '.$event->user->l_name, $role->name));
      }

      $data = [
         'email' => $eventUser->email,
         'subject' => 'Invitation to collaborate in Event Management',
         'message' => 'This is an invitation sent by '. $event->user->f_name.' '.$event->user->l_name .' to you for assistance in the management of the event '. $event->event_name . ' in the role '. $role->name .'. Click on the button below to login and access the details.',
         'event_id' => $event->id,
         'role_id' => $role->id,
      ];

      SendEventUserInvite::dispatchAfterResponse($data);

      $eventUser->isSent = 1;
      $eventUser->save();

      session()->flash('success', 'Commitee Member Added');

      return back();
   }

   /**
    *
    * Update Event User
    *
    */
   public function editEventUser(Request $request)
   {
      $role = Role::where('name', $request->role)->first();

      $user = EventUser::find($request->user_id);
      $user->names = $request->names;
      $user->email = $request->email;
      $user->role = $request->role;
      $user->isSent = false;
      $user->save();

      $userRole = UserRole::where('user_id', $user->user_id)->where('event_id', $user->event_id)->first();

      if ($userRole) {
         $userRole->update([
            'role_id' => $role->id
         ]);
      }

      $registeredUser = User::where('email', $request->email)->first();
      if ($registeredUser) {
         $event = Event::where('id', $user->event_id)->with('user')->first();
         $role = Role::where('name', $request->role)->first();
         $user->update([
            'user_id' => $registeredUser->id
         ]);

         $registeredUser->notify(new UserEventNotification($event->id, $event->event_name, $event->user->f_name.' '.$event->user->l_name, $role->name));
      }

      return back()->with('success', 'User details updated');
   }

   /**
    *
    * Get Event Users with Roles
    *
    */
   public function eventsRoles()
   {
      $roles = UserRole::with('event', 'user', 'role')->where('user_id', auth()->user()->id)->get();

      return view('events.events-roles', compact('roles'));
   }

   /**
    *
    * Delete Event User
    *
    */
   public function eventsRoleDelete(Request $request)
   {
      $role = UserRole::find($request->role_id);

      EventUser::where([
         'event_id' => $role->event_id,
         'user_id' => $role->user_id,
      ])->delete();

      $role->delete();

      return back()->with('success', 'The Role was removed');
   }

   /**
    *
    * Show Event Order
    *
    */
   public function viewEventOrder($eventOrder)
   {
      $requestOrder = explode(' ', $eventOrder);
      $order = Order::where('order_id', $requestOrder[1])->first();
      return redirect()->route('client.orders.order', $order);
   }

   /**
    *
    * Download Sample Event Guest List
    *
    */
   public function downloadSampleGuestList()
   {
      $file = public_path(). "/storage/event/guest/Sample Guest List-Tupange.xlsx";
      return response()->download($file, 'Sample Guest List-Tupange.xlsx');
   }

   public function eventServiceOrder(Request $request, $id)
   {
      $request->session()->put('event_id', $id);

      return redirect()->route('client.services.all');
   }

   /**
    *
    * Send Tast Reminder To Event User
    *
    */
   public function sendTaskReminder($id)
   {
      $task = EventTask::find($id);

      if (!$task) {
         return response()->json(['message' => 'Task not found'], 422);
      }

      SendTaskReminder::dispatchAfterResponse($task->event, $task);

      return response()->json(['message' => 'Task Reminder Sent Successfully'], 200);
   }

   /**
    *
    * View Event User Ticket
    *
    */
   public function viewTicket($id)
   {
      $guest = EventGuestDetail::find($id);

      $pdf = PDF::loadView('partials.event-ticket-pdf', ['guest' => $guest, 'map' => true]);

      return $pdf->stream('ticket.pdf');
   }

   /**
    *
    * Update Attended Guests for event ticket
    *
    */
   public function eventTicketUpdateGuestsAttended(Request $request, $ticket)
   {
      $request->validate([
         'guests_attended' => 'required|numeric'
      ]);

      $guest_ticket = EventGuestDetail::find($ticket);

      if ($request->guests_attended > $guest_ticket->guests) {
         session()->put('error', 'The number entered exceeds those that can attend for this ticket.');
         return back();
      } elseif ($request->guests_attended > ($guest_ticket->guests - $guest_ticket->guests_attended)) {
         session()->put('error', 'The number entered exceeds those that can attend for this ticket.');
         return back();
      }

      $guest_ticket->update([
         'guests_attended' => $request->guests_attended,
      ]);

      if ($guest_ticket->guests_attended === $guest_ticket->guests) {
         $guest_ticket->update([
            'status' => 'Attended',
         ]);
      }

      session()->put('success', 'Guest attendance updated');
      return back();
   }

   /**
    *
    * Ticket Mpesa Payment
    *
    */
   public function ticketPayment(Request $request, $ticket)
   {
      Validator::make($request->all(), [
         'first_name' => 'required',
         'last_name' => 'required',
         'email' => 'required',
         'phone_number' => 'required',
      ])->validate();

      $account_reference = Str::upper(Str::random(3)).time().Str::upper(Str::random(3));
      $phone_number = substr($request->phone_number, -9);
      $phone_number = '254'.$phone_number;
      $ticket = EventGuestDetail::find($ticket);

      $token = Pesapal::accessToken();
      $url = Pesapal::baseUrl().'/api/Transactions/SubmitOrderRequest';
      $notification_id = PesapalNotificationUrl::where('payment_purpose', 'Ticket Payment')->first()->ipn_id;
      $response = Http::timeout(3)
         ->withToken($token)->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
         ])
         ->post($url, [
            'id' => $account_reference,
            'currency' => 'KES',
            // TODO: Change Amount to be actual amount in prod
            // 'amount' => (double) $ticket->ticket_price * (int) $ticket->guests,
            'amount' => 100,
            'description' => 'Payment of Event Ticket',
            'callback_url' => route('pesapal.ticket.payment.success.callback'),
            'cancellation_url' => route('pesapal.ticket.payment.failed.callback'),
            'notification_id' => $notification_id,
            'billing_address' => [
               'phone_number' => $request->phone_number,
               'email_address' => $request->email,
               'country_code' => 'KE',
               'first_name' => $request->first_name,
               'last_name' => $request->last_name,
            ]
         ]);

      PesapalPayment::create([
         'payable_id' => $ticket->id,
         'payable_type' => EventGuestDetail::class,
         'tracking_id' => json_decode($response)->order_tracking_id,
      ]);

      EventTicketPayment::create([
         'event_guest_detail_id' => $ticket->id,
         'amount' => $ticket->ticket_price,
         'paid_by_name' => $request->first_name.' '.$request->last_name,
         'paid_by_email' => $request->email,
      ]);

      $redirect_url = json_decode($response)->redirect_url;
      return redirect($redirect_url);
   }

   /**
    *
    * Ticket Mpesa Payment callback
    *
    */
   public function ticketPaymentMpesaCallback(Request $request)
   {
      info($request);
   }

   /**
    *
    * Ticket Card Payment Success callback
    *
    */
   public function ticketPaymentIveriSuccessCallback(Request $request)
   {
      info($request);
   }

   /**
    *
    * Ticket Card Payment Failed callback
    *
    */
    public function ticketPaymentIveriFailedCallback(Request $request)
   {
      info($request);
   }
}
