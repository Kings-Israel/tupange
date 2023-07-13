<?php

namespace App\Http\Controllers;

use Image;
use App\Models\Order;
use App\Models\Service;
use App\Models\Category;
use App\Models\Messages;
use App\Rules\PhoneNumber;
use Illuminate\Http\Request;
use App\Models\ServiceGallery;
use App\Models\ServicePricing;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
   public function vendorServices()
   {
      $services = Service::where('vendor_id', Auth::user()->vendor->id)->orderBy('created_at', 'DESC')->get();

      return view('vendor.services')->with(['services' => $services]);
   }

   public function clientServices(?string $category = 'All')
   {
      if ($category != 'All') {
         return view('client.services')->with(['category' => $category]);
      }
      return view('client.services');
   }

   public function clientServicesFiltered(Request $request)
   {
      return view('client.services')->with($request->all());
   }

   public function clientService(Service $service)
   {
      $service->load('vendor');
      $services = Service::where('vendor_id', $service->vendor->id)->where('id', '!=', $service->id)->where('service_status_id', 1)->take(6)->get();
      return view('client.service')->with(['service' => $service, 'services' => $services]);
   }

   public function vendorService(Service $service)
   {
      $service->load('service_pricing', 'service_images');
      return view('vendor.service')->with(['service' => $service]);
   }



   public function addService()
   {
      $categories = Category::all();
      $vendor = auth()->user()->vendor;
      return view('vendor.add_service')->with(['categories' => $categories, 'vendor' => $vendor]);
   }

   public function editService(Service $service)
   {
      $categories = Category::all();
      $vendor = $service->vendor;
      return view('vendor.edit_service')->with(['service' => $service, 'categories' => $categories, 'vendor' => $vendor]);
   }

   public function submitEditService(Request $request)
   {
      $rules = [
         'service_title' => ['required', 'string'],
         'service_category' => ['required'],
         'service_description' => ['required'],
         'service_location_map' => ['required'],
      ];

      // Check if checkbox is not marked and add rules
      if (! $request->has('use_company_contacts')) {
         $rules['service_contact_email'] = 'required';
         $rules['service_contact_phone_number'] = 'required';
      }

      $messages = [
         '*.required' => 'Please fill this field',
         'service_cover_image.image' => 'Please upload a valid image'
      ];

      // $location = explode(',', $request->service_location_map);
      // // Format longitude (remove the ending bracket)
      // substr($location[1], 0, -1);

      // $lat = (double) ltrim($location[0], $location[0][0]);
      // $long = (double) ltrim($location[1], $location[1][0]);

      Validator::make($request->all(), $rules, $messages)->validate();

      if ($request->has('place_id') && $request->place_id != null) {
         // Get location string
         $service_location = Http::get('https://maps.googleapis.com/maps/api/geocode/json?place_id='.$request->place_id.'&key='.config('services.maps.partial_key'));
         $service_location = json_decode($service_location->body());

         $lat = $service_location->results[0]->geometry->location->lat;
         $long = $service_location->results[0]->geometry->location->lng;
      }

      $service = Service::find($request->service_id);

      $service->update([
         'service_title' => $request->service_title,
         'category_id' => $request->service_category,
         'service_description' => $request->service_description,
         'service_contact_email' => $request->has('use_company_contacts') ? Auth::user()->vendor->company_email : $request->service_contact_email,
         'service_contact_phone_number' => $request->has('use_company_contacts') ? Auth::user()->vendor->company_phone_number : $request->service_contact_phone_number,
         'service_location_lat' => $request->has('place_id') && $request->place_id != null ? $lat : $service->service_location_lat,
         'service_location_long' => $request->has('place_id') && $request->place_id != null ? $long : $service->service_location_long,
         'service_location' => $request->has('place_id') && $request->place_id != null ? $request->service_location_map : $service->service_location,
      ]);

      if ($request->has('service_cover_image')) {
         Storage::disk('service')->delete('cover_image/'.$service->service_image);
         $image = $request->file('service_cover_image');
         $input['imagename'] = time().'.'.$image->extension();

         $filePath = public_path('storage/service/cover_image');
         $img = Image::make($image->path());
         $img->resize(700, 464, function($const) {
            $const->aspectRatio();
         })->save($filePath.'/'.$input['imagename']);
         $service->update([
               // 'service_image' => pathinfo($request->service_cover_image->store('cover_image', 'service'), PATHINFO_BASENAME)
               'service_image' => $img->basename
         ]);
      }


      return redirect()->back()->with('success', 'Service details updated');
   }

   public function submitService(Request $request)
   {
      $rules = [
         'service_title' => ['required', 'string'],
         'service_category' => ['required'],
         'service_description' => ['required'],
         'place_id' => ['required'],
         'service_cover_image' => ['required', 'image', 'mimes:jpg,jpeg,png']
      ];

      // Check if checkbox is not marked and add rules
      if (! $request->has('use_company_contacts')) {
         $rules['service_contact_email'] = ['required', 'email'];
         $rules['service_contact_phone_number'] = ['required'];
      }

      if ($request->has('add_service_pricing')) {
         $rules['service_pricing_title'] = ['required'];
         $rules['service_pricing_price'] = ['required'];
         $rules['service_pricing_description'] = ['required'];
      }

      $messages = [
         'place_id.required' => 'Please select or search a location on the map',
         '*.required' => 'Please fill this field',
         'service_cover_image.image' => 'Please upload a valid image'
      ];

      // $validated_data = $request->validate($rules, $messages);
      Validator::make($request->all(), $rules, $messages)->validate();

      // Get location string
      $service_location = Http::get('https://maps.googleapis.com/maps/api/geocode/json?place_id='.$request->place_id.'&key='.config('services.maps.partial_key'));
      $service_location = json_decode($service_location->body());

      $lat = $service_location->results[0]->geometry->location->lat;
      $long = $service_location->results[0]->geometry->location->lng;

      // Save cover image
      $image = $request->file('service_cover_image');
      $input['imagename'] = time().'.'.$image->extension();

      $filePath = public_path('storage/service/cover_image');
      $img = Image::make($image->path());
      $img->resize(700, 464, function($const) {
         $const->aspectRatio();
      })->save($filePath.'/'.$input['imagename']);

      $service = Service::create([
         'vendor_id' => Auth::user()->vendor->id,
         'category_id' => $request->service_category,
         'service_title' => $request->service_title,
         'service_description' => $request->service_description,
         // 'service_image' => pathinfo($request->service_cover_image->store('cover_image', 'service'), PATHINFO_BASENAME),
         'service_image' => $img->basename,
         'service_contact_email' => $request->has('use_company_contacts') ? Auth::user()->vendor->company_email : $request->service_contact_email,
         'service_contact_phone_number' => $request->has('use_company_contacts') ? Auth::user()->vendor->company_phone_number : $request->service_contact_phone_number,
         'service_location_lat' => $lat,
         'service_location_long' => $long,
         'service_location' => $request->has('place_id') && $request->place_id != NULL ? $service_location->results[0]->formatted_address : $request->service_location_map,
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
               'image' => $img->basename,
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
            'service_pricing_price' => $request->service_pricing_price,
            'service_pricing_description' => $request->service_pricing_description,
            'service_packages' => $packages
         ]);
      }

      if($service) {
         if($request->action == "Submit") {
               return redirect()->route('vendor.services.all')->with('success', "Service added");
         } else if ($request->action == 'Submit and Add') {
               return redirect()->back()->with('success', 'Service added');
         }
      } else {
         return redirect()->back()->with('error', 'Error adding service');
      }
   }

   public function deleteServicePermanently(Request $request)
   {
      // Check if service has any pending orders
      $orders = Order::where('service_id', $request->service_id)->where('status', 'Paid')->orWhere('status', 'Dispute')->get();
      if ($orders->count()) {
         return back()->with('error', 'There are pending or unsettled orders under this service');
      } else {
         $orders = Order::where('service_id', $request->service_id)->get();
         $orders->each(function($order) {
            // Delete all messages if no pending orders
            Messages::where('order_id', $order->order_id)->delete();
            // Delete Order
            $order->delete();
         });

         $serviceGallery = ServiceGallery::where('service_id', $request->service_id)->get();
         $serviceGallery->each(function($image) {
            Storage::disk('service')->delete('images', $image->image);
            $image->delete();
         });

         $service = Service::find($request->service_id);
         Storage::disk('service')->delete('cover_image', $service->service_image);
         $service->forceDelete();
      }

      return redirect()->route('vendor.services.all')->with('success', 'Service deleted');
   }

   public function submitServicePricing(Request $request)
   {
      $rules = [
         'service_pricing_title' => 'required',
         'service_pricing_price' => 'required',
         'service_pricing_description' => 'required'
      ];

      $validator = Validator::make($request->all(), $rules);

      if ($validator->fails()) {
         return $request->wantsJson()
                  ? new JsonResponse(['errors' => $validator->errors()], 422)
                  : redirect()->back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
      }

      // Check service pricing price
      $price = explode(',', $request->service_pricing_price);
      $service_pricing = implode($price);
      if (!ctype_digit($service_pricing)) {
         return $request->wantsJson()
            ? new JsonResponse(['errors' => 'Invalid Service Price Value'], 400)
            : redirect()->back()->withInput()->with('error', "Check the service pricing. Value is not an integer");
      }

      $packages = [];
      if ($request->service_packages) {
         foreach($request->service_packages as $index => $new_package){
               if ($new_package != null) {
                  array_push($packages, $new_package);
               }
         }
      }

      ServicePricing::create([
         'service_id' => $request->service_id,
         'service_pricing_title' => $request->service_pricing_title,
         'service_pricing_price' => $service_pricing,
         'service_pricing_description' => $request->service_pricing_description,
         'service_packages' => $packages
      ]);

      $redirectPath = redirect()->back()->with('success', 'Service package added')->getTargetUrl();
      return $request->wantsJson()
                  ? new JsonResponse(['redirectPath' => $redirectPath], 200)
                  : back()->with('success', 'Service package added');
   }

   public function submitServiceImages(Request $request)
   {
      $rules = [
         'service_image' => ['required', 'image']
      ];

      $messages = [
         'service_image.required' => 'Please upload an image',
         'service_image.image' => "Select a valid image"
      ];

      Validator::make($request->all(), $rules, $messages)->validate();

      $image = $request->file('service_image');
      $input['imagename'] = time().'.'.$image->extension();

      $filePath = public_path('storage/service/images');
      $img = Image::make($image->path());
      $img->resize(700, 464, function($const) {
         $const->aspectRatio();
      })->save($filePath.'/'.$input['imagename']);

      $image = ServiceGallery::create([
         'service_id' => $request->service_id,
         'image' => $img->basename,
         'image_description' => $request->service_image_description,
      ]);

      if ($image) {
         return back()->with('success', 'Image uploaded successfully');
      }

      return back()->with('error', 'An error occurred. Please try again.');
   }

   public function deleteServiceImage(Request $request)
   {
      // dd($request->all());
      $deleteImage = Storage::disk('service')->delete('images/'.$request->image);

      if ($deleteImage) {
         ServiceGallery::where('image', $request->image)->delete();
      }

      return back()->with('success', 'Image successfully deleted');
   }

   public function submitServicePricingUpdate(Request $request)
   {
      // Check service pricing price
      $price = explode(',', $request->service_pricing_price);
      $service_pricing_price = implode($price);
      if (!ctype_digit($service_pricing_price)) {
         return $request->wantsJson()
            ? new JsonResponse(['errors' => 'Invalid Service Price Value'], 400)
            : redirect()->back()->withInput()->with('error', "Check the service pricing. Value is not an integer");
      }

      $packages = [];
      if ($request->service_packages_edit) {
         foreach($request->service_packages_edit as $index => $new_package){
               if ($new_package != null) {
                  array_push($packages, $new_package);
               }
         }
      }
      $service_pricing = ServicePricing::find($request->pricing_id);
      $service_pricing->service_pricing_title = $request->service_pricing_title;
      $service_pricing->service_pricing_price = $service_pricing_price;
      $service_pricing->service_pricing_description = $request->service_pricing_description;
      $service_pricing->service_packages = $packages;
      $service_pricing->save();

      $redirectPath = redirect()->back()->with('success', 'Pricing details updated')->getTargetUrl();
      return $request->wantsJson()
                  ? new JsonResponse(['redirectPath' => $redirectPath], 200)
                  : back()->with('success', 'Pricing details updated');
   }

   public function pauseAllServices()
   {
      $vendor = Auth::user()->vendor;
      $services = Service::where('vendor_id', $vendor->id)->get();
      foreach ($services as $service) {
         $service->service_status_id = 2;
         $service->save();
      }

      return back()->with('success', 'Paused all services');
   }

   public function resumeAllServices()
   {
      $vendor = Auth::user()->vendor;
      $services = Service::where('vendor_id', $vendor->id)->get();
      foreach ($services as $service) {
         $service->service_status_id = 1;
         $service->save();
      }

      return back()->with('success', 'Resumed all services');
   }

   public function pauseService(Request $request)
   {
      $rules = [
         'service_id' => 'required',
      ];

      $messages = [
         'service_id.requried' => 'There was an error. Please try again',
      ];

      if ($request->action == 'Pause') {
         $rules = [
            'pause_from' => 'required',
            'pause_until' => 'required'
         ];

         $messages = [
            'pause_from.requried' => 'Please select the date from which to pause from',
            'pause_until.requried' => 'Please select the date from which to pause to',
         ];
      }

      $validator = Validator::make($request->all(), $rules, $messages);

      if ($validator->fails()) {
         return Redirect::back()->withErrors($validator)->withInput()->with('error', $validator->errors()->first());
      }

      $service = Service::find($request->service_id);

      if (!$service) {
         return back()->with('error', 'The service selected was not found');
      } else {
         if ($request->action == 'Pause') {
            $service->pause_from = $request->pause_from;
            $service->pause_until = $request->pause_until;
            if ($request->pause_from === now()->format('Y-m-d')) {
               $service->service_status_id = 2;
            }
         } else {
            $service->service_status_id = 2;
         }

         if ($request->pause_note != null) {
            $service->pause_note = $request->pause_note;
         }

         $service->save();

         $redirectPath = redirect()->back()->with('success', 'Service status updated successfully')->getTargetUrl();
         return $request->wantsJson()
                  ? new JsonResponse(['redirectPath' => $redirectPath], 200)
                  : back()->with('success', 'Service status updated');
      }
   }

   public function resumeService(Request $request)
   {
      $rules = [
         'service_id' => 'required'
      ];

      $messages = [
         'service_id.requried' => 'There was an error. Please try again'
      ];

      Validator::make($request->all(), $rules, $messages)->validate();

      $service = Service::find($request->service_id);

      if (!$service) {
         return back()->with('error', 'The service selected was not found');
      } else {
         if ($service->pause_from != null) {
            $service->pause_from = null;
         }
         if ($service->pause_until != null) {
            $service->pause_until = null;
         }
         $service->service_status_id = 1;
         $service->save();

         return back()->with('success', 'The service was resumed');
      }
   }

   public function addToCart(Service $service, ?ServicePricing $pricing)
   {
      $cart = session()->get('cart');

      // if cart is empty then this the first product
      if(!$cart) {
         $cart = [
            $service->id => [
               "service_title" => $service->service_title,
               'service_description' => $service->service_description,
               "added_on" => now(),
               "pricing" => $pricing ? $pricing->id : null
            ]
         ];
         session()->put('cart', $cart);

         return redirect()->back()->with('success', 'Service added to cart successfully!');
      }

      // if cart not empty then check if this product exist then increment quantity
      if(isset($cart[$service->id])) {
         return redirect()->back()->with('error', 'Service already in cart!');
      }

      // if item not exist in cart then add to cart with quantity = 1
      $cart[$service->id] = [
         "service_title" => $service->service_title,
         "added_on" => now(),
         "pricing" => $pricing ? $pricing->id : null
      ];

      session()->put('cart', $cart);

      return redirect()->back()->with('success', 'Service added to cart successfully!');
   }

   public function viewCart()
   {
      $services = [];
      if (session('cart')) {
         foreach(session('cart') as $item => $details){
            array_push($services, Service::with('service_pricing')->find($item));
         }
      }
      return view('cart', compact('services'));
   }

   public function removeFromCart(Request $request)
    {
        if($request->id) {

            $cart = session()->get('cart');

            if(isset($cart[$request->id])) {

                unset($cart[$request->id]);

                session()->put('cart', $cart);
            }

            session()->flash('success', 'Service removed successfully');
        }
    }
}
