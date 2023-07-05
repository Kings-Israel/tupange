<?php

namespace App\Http\Controllers;

use App\Jobs\SendOrderUpdateMail;
use App\Models\Category;
use App\Models\Messages;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServicePricing;
use App\Models\OrderQuotations;
use App\Models\ServiceGallery;
use App\Models\Vendor;
use App\Notifications\ClientNotification;
use App\Rules\PhoneNumber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Image;

class VendorController extends Controller
{
   public function __construct()
   {
      $this->middleware(['auth', 'auth.session']);
   }
   public function complete()
   {
      $categories = Category::all();
      return view('vendor.vendor_complete', compact('categories'));
   }

   public function dashboard()
   {
      $vendor = Auth::user()->vendor;
      $services_count = $vendor->loadCount(['services' => function($query){
         $query->where('service_status_id', 1);
      }]);
      $orders_count = 0;
      foreach ($vendor->services as $service) {
         $orders_count += $service->loadCount(['orders' => function($query) {
            return $query->where('status', '!=', 'Archived')->where('status', '!=', 'Declined');
         }])->orders_count;
      }
      $reviews_count = 0;
      foreach ($vendor->services as $service) {
         $reviews_count += $service->loadCount('reviews')->reviews_count;
      }
      $orders = $vendor->load(['orders' => function($query) {
         $query->orderBy('created_at', 'DESC')->take(4);
      }]);
      $reviews = $vendor->load(['reviews' => function($query) {
         $query->orderBy('created_at', 'DESC')->take(4);
      }]);

      return view('vendor.dashboard')->with([
         'vendor' => $vendor,
         'orders' => $orders,
         'services_count' => $services_count->services_count,
         'orders_count' => $orders_count,
         'reviews_count' => $reviews_count,
         'reviews' => $reviews,
      ]);
   }

   public function orderSummary(Vendor $vendor)
   {
      $ordersStatusSummary = [];
      $statuses = ['Sent', 'Received', 'Paid', 'Completed'];
      foreach ($statuses as $status) {
         $total_amount = 0;
         $vendorOrders = $vendor->load(['orders' => function($query) use ($status) {
                              $query->where('orders.status', $status);
                           }]);
         foreach ($vendorOrders->orders as $order) {
            if ($order->payment()->count()> 0) {
               $total_amount += $order->payment->amount;
            } elseif ($order->service_pricing_id != null) {
               $total_amount += $order->service_pricing->service_pricing_price;
            } elseif ($order->order_quotation_id != null) {
               $total_amount += $order->order_quotation->order_pricing_price;
            } else {
               $total_amount += 0;
            }
         }
         $summary = [
            'status' => $status,
            'number' => count($vendorOrders->orders),
            'total_amount' => $total_amount
         ];
         array_push($ordersStatusSummary, $summary);
      }

      return $ordersStatusSummary;
   }

   public function orders()
   {
      $vendor = Auth::user()->vendor;
      $ordersSummary = $this->orderSummary($vendor);
      $orders = $vendor->load('orders');
      $categories = $orders->orders->map(function ($order) {
         return $order->service->category;
      });
      return view('vendor.orders')->with(['vendor' => $vendor, 'ordersSummary' => $ordersSummary, 'categories' => $categories->unique()]);
   }

   public function reviews()
   {
      $vendor = Auth::user()->vendor;
      return view('vendor.reviews-view', compact('vendor'));
   }

   public function order($id)
   {
      $order = Order::find($id);
      if (! $order) {
         return back()->with('error', 'The order no longer exists');
      }

      $vendor = $order->service->vendor;
      $vendor->loadCount(['orders' => function($query) {
         return $query->where('orders.status', 'Cancelled')->whereBetween('orders.updated_at', [Carbon::now()->subMonths(3), Carbon::now()]);
      }]);
      $cancelCount = $vendor->orders_count;

      $order_quotations = OrderQuotations::where('order_id', $order->id)->get();
      return view('vendor.order')->with(['order' => $order, 'order_quotations' => $order_quotations, 'cancelCount' => $cancelCount]);
   }

   public function deleteOrder(Order $order)
   {
      $order->delete();

      if ($order->review()->exists()) {
         $order->review()->delete();
      }

      // $order->messages()->delete();
      $messages = Messages::where('order_id', $order->order_id)->get();
      if ($messages) {
         collect($messages)->each(fn ($message) => $message->delete());
      }
      $order->forceDelete();

      return redirect()->route('vendor.orders.all')->with('success', 'Order deleted successfully');
   }

   public function cancelOrder(Request $request)
   {
      $order = Order::find($request->order_id);

      if (! $order) {
         return redirect()->route('vendor.orders.all')->with('error', 'The Order was not found');
      }

      $order->update([
         'status' => 'Cancelled'
      ]);

      $order->user->notify(new ClientNotification($order, 'Order Cancelled'));

      $vendor = $order->service->vendor;
      $vendor->loadCount(['orders' => function($query) {
         return $query->where('orders.status', 'Cancelled')->whereBetween('orders.updated_at', [Carbon::now()->subMonths(3), Carbon::now()]);
      }]);
      $cancelCount = $vendor->orders_count;

      if ($cancelCount > 3) {
         $vendor->update([
            'status' => 'Suspended'
         ]);
         Service::where('vendor_id', $vendor->id)->update(['service_status_id' => 2]);
      }

      return back()->with('success', 'Order status was updated successfully');
   }

   public function profile()
   {
      $vendor = Auth::user()->vendor;
      return view('vendor.vendor_profile')->with(['vendor' => $vendor]);
   }

   public function createCompany(Request $request)
   {
      $rules = [
         'company_name' => 'required|string',
         'company_email' => ['required', 'email', 'unique:vendors'],
         'company_phone_number' => ['required', 'unique:vendors'],
         'company_description' => 'required|string|min:10',
         'location_map' => 'required',
         'company_logo' => 'required|image|mimes:jpg,png,jpeg'
      ];

      $messages = [
         '*.required' => 'Please fill this field',
         'location_map.required' => 'Please select a location',
         'company_email' => 'Please enter a valid email',
         'company_logo.image' => 'Please upload a valid image'
      ];

      if($request->has('add_service')) {
            $rules['service_title'] = ['required', 'string'];
            $rules['service_category'] = ['required'];
            $rules['service_description'] = ['required'];
            $rules['service_location_map'] = ['required'];
            $rules['service_cover_image'] = ['required', 'image', 'mimes:jpg,jpeg,png'];

         // Check if checkbox is not marked and add rules
         if (! $request->has('use_company_contacts')) {
            $rules['service_contact_email'] = ['required', 'email'];
            $rules['service_contact_phone_number'] = ['required'];
         }

         $messages['service_cover_image.image'] = 'Please upload a valid image';
         $messages['service_location_map.required'] = 'Please select a location';
      }

      if ($request->has('add_service_pricing')) {
         $rules['service_pricing_title'] = ['required'];
         $rules['service_pricing_price'] = ['required'];
         $rules['service_pricing_description'] = ['required'];
      }

      $validator = Validator::make($request->all(), $rules, $messages);

      if ($validator->fails()) {
         return $request->wantsJson()
                  ? new JsonResponse(['errors' => $validator->errors()], 422)
                  : redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
      }

      // Check service pricing price
      if ($request->has('add_service_pricing')) {
         $price = explode(',', $request->service_pricing_price);
         $service_pricing = implode($price);
         if (!ctype_digit($service_pricing)) {
            return $request->wantsJson()
               ? new JsonResponse(['errors' => 'Invalid Service Price Value'], 400)
               : redirect()->back()->withInput()->with('error', "Check the service pricing. Value is not an integer");
         }
      }

      $vendor_location = Http::get('https://maps.googleapis.com/maps/api/geocode/json?place_id='.$request->place_id.'&key='.config('services.maps.partial_key'));
      $vendor_location = json_decode($vendor_location->body());

      $lat = $vendor_location->results[0]->geometry->location->lat;
      $long = $vendor_location->results[0]->geometry->location->lng;

      $image = $request->file('company_logo');
      $input['imagename'] = time().'.'.$image->extension();

      $filePath = public_path('storage/vendor/logo');
      $img = Image::make($image->path());
      $img->resize(700, 464, function($const) {
         $const->aspectRatio();
      })->save($filePath.'/'.$input['imagename']);

      $vendor = Vendor::create([
         'user_id' => auth()->id(),
         'company_name' => $request->company_name,
         'company_email' => $request->company_email,
         'company_phone_number' => $request->company_phone_number,
         'company_description' => strip_tags($request->company_description),
         'location' => $vendor_location->results[0]->formatted_address,
         'location_lat' => $lat,
         'location_long' => $long,
         'company_logo' => $img->basename,
         'status' => 'Active'
      ]);

      $user = Auth::user();
      $user->status = 'vendor';
      $user->save();

      if ($request->has('add_service')) {
         // // Get location string
         $service_location = Http::get('https://maps.googleapis.com/maps/api/geocode/json?place_id='.$request->place_service_id.'&key='.config('services.maps.partial_key'));
         $service_location = json_decode($service_location->body());

         $lat = $service_location->results[0]->geometry->location->lat;
         $long = $service_location->results[0]->geometry->location->lng;

         $image = $request->file('service_cover_image');
         $input['imagename'] = time().'.'.$image->extension();

         $filePath = public_path('storage/service/cover_image');
         $img = Image::make($image->path());
         $img->resize(700, 464, function($const) {
            $const->aspectRatio();
         })->save($filePath.'/'.$input['imagename']);

         $service = Service::create([
            'vendor_id' => $vendor->id,
            'category_id' => $request->service_category,
            'service_title' => $request->service_title,
            'service_description' => $request->service_description,
            'service_image' => $img->basename,
            'service_contact_email' => $request->has('use_company_contacts') ? $vendor->company_email : $request->service_contact_email,
            'service_contact_phone_number' => $request->has('use_company_contacts') ? $vendor->company_phone_number : $request->service_contact_phone_number,
            'service_location_lat' => $lat,
            'service_location_long' => $long,
            'service_location' => $request->service_location_map
         ]);

         if ($request->has('service_gallery')) {
            collect($request->service_gallery)->each(function ($image) use ($service) {
               $input['imagename'] = time().'.'.$image->extension();

               $filePath = public_path('storage/service/images');
               $img = Image::make($image->path());
               $img->resize(700, 464, function($const) {
                  $const->aspectRatio();
               })->save($filePath.'/'.$input['imagename']);

               ServiceGallery::create([
                  'service_id' => $service->id,
                  'image' => $img->basename
               ]);
               sleep(1);
            });
         }

         if ($request->has('add_service_pricing')) {
            $packages = [];
            if ($request->service_packages) {
               foreach($request->service_packages as $index => $new_package){
                     if ($new_package != null) {
                        array_push($packages, $new_package);
                     }
               }
            }

            ServicePricing::create([
               'service_id' => $service->id,
               'service_pricing_title' => $request->service_pricing_title,
               'service_pricing_price' => $service_pricing,
               'service_pricing_description' => $request->service_pricing_description,
               'service_packages' => $packages
            ]);
         }

         $redirectPath = redirect()->route('vendor.services.one', $service)->with('success', 'Profile created successfuly')->getTargetUrl();
         return $request->wantsJson()
                  ? new JsonResponse(['redirectPath' => $redirectPath], 200)
                  : redirect()->route('vendor.services.one', $service)->with('success', 'Profile created successfuly');
      }

      $redirectPath = redirect()->route('vendor.dashboard')->with('success', 'Profile created successfuly')->getTargetUrl();
      return $request->wantsJson()
               ? new JsonResponse(['redirectPath' => $redirectPath], 200)
               : redirect()->route('vendor.dashboard')->with('success', 'Profile created successfully');
   }

   public function submitProfile(Request $request)
   {
      $rules = [
         'vendor_id' => 'required',
         'company_name' => 'required|string',
         'company_description' => 'required|string|min:10',
         'company_phone_number' => ['required', 'min:9', 'max:12', Rule::unique('vendors')->ignore($request->user()->vendor->id, 'id')],
         'company_email' => ['required', 'email', Rule::unique('vendors')->ignore($request->user()->vendor->id, 'id')],
      ];

      $messages = [
         '*.required' => 'Please fill this field',
         'location_map.required' => 'Please select a location',
         'company_email' => 'Please enter a valid email',
      ];

      Validator::make($request->all(), $rules, $messages)->validate();

      $vendor = Vendor::find($request->vendor_id);

      if ($request->has('place_id') && $request->place_id != NULL) {
         $vendor_location = Http::get('https://maps.googleapis.com/maps/api/geocode/json?place_id='.$request->place_id.'&key='.config('services.maps.partial_key'));
         $vendor_location = json_decode($vendor_location->body());

         $lat = $vendor_location->results[0]->geometry->location->lat;
         $long = $vendor_location->results[0]->geometry->location->lng;
      }


      $vendor->update([
         'company_name' => $request->company_name,
         'company_description' => $request->company_description,
         'location_lat' => $request->has('place_id') && $request->place_id != NULL ? $lat : $vendor->location_lat,
         'location_long' => $request->has('place_id') && $request->place_id != NULL ? $long : $vendor->location_long,
         'location' => $request->has('place_id') && $request->place_id != NULL ? $vendor_location->results[0]->formatted_address : $vendor->location,
      ]);

      if ($request->has('company_logo')) {
         Storage::disk('vendor')->delete('logo/'.$vendor->company_logo);
         $image = $request->file('company_logo');
         $input['imagename'] = time().'.'.$image->extension();

         $filePath = public_path('storage/vendor/logo');
         $img = Image::make($image->path());
         $img->resize(700, 464, function($const) {
            $const->aspectRatio();
         })->save($filePath.'/'.$input['imagename']);
         $vendor->update([
               'company_logo' => $img->basename,
         ]);
      }

      return redirect()->route('vendor.dashboard')->with('success', 'Vendor Profile Updated');
   }

   public function get_vendors()
   {
      $vendors = Vendor::all();

      return view('vendors')->with(['vendors' => $vendors]);
   }

   public function deleteVendor($id)
   {
      $vendor = Vendor::find($id);

      Storage::disk('vendor')->delete('logo/'.$vendor->company_logo);

      $vendor->delete();

      return back()->with('success', 'Vendor Deleted');
   }

   public function addCustomQuote(Request $request)
   {
      $order = Order::find($request->order_id);
      if (! $order) {
         $vendor = Auth::user()->vendor;
         return view('vendor.orders')->with(['vendor' => $vendor, 'error' => 'The order no longer exists!!']);
      }

      $orderQuotation = new OrderQuotations();
      $orderQuotation->order_id = $request->order_id;
      $orderQuotation->order_pricing_title = $request->order_pricing_title;
      $orderQuotation->order_pricing_price = $request->order_pricing_price;
      $orderQuotation->order_pricing_agreement = $request->order_pricing_agreement;
      $orderQuotation->save();

      $order = Order::find($request->order_id);
      $order->user->notify(new ClientNotification($order, 'Added Quote'));

      // Send Email to client
      $data = [];
      $data['email'] = $order->user->email;
      $data['subject'] = 'Order Custom Quote';
      $data['content'] = 'The vendor for the service '.$order->service->service_title.' created a custom quotation for your order. Please login below to view the details.';

      SendOrderUpdateMail::dispatchAfterResponse($data);

      return back()->with('success', 'Custom quotation created');
   }

   public function noCompany()
   {
      $user = auth()->user();

      Vendor::create([
         'user_id' => auth()->id(),
         'company_name' => $user->f_name.' '.$user->l_name,
         'company_email' => $user->email,
         'company_phone_number' => $user->phone_number,
         'company_description' => $user->f_name.' '.$user->l_name,
         'location' => ("(-1.270104, 36.80814)"),
         'company_logo' => 'default.png'
      ]);

      $user->status = 'vendor';
      $user->save();

      return redirect()->route('vendor.dashboard')->with('success', 'Vendor Profile Created.');
   }
}
