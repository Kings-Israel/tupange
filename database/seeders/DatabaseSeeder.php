<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CustomerReview;
use App\Models\Event;
use App\Models\EventTask;
use App\Models\Service;
use App\Models\ServiceStatus;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       ServiceStatusSeeder::class;
       Role::factory()->create([
          'name' => 'Committee'
       ]);
       Role::factory()->create([
          'name' => 'Tasks'
       ]);

        $user = User::factory()->create([
            'f_name' => 'Test',
            'l_name' => 'User',
            'email' => 'test@user.com',
        ]);

        $user2 = User::factory()->create([
            'f_name' => 'Test',
            'l_name' => 'User 2',
            'email' => 'test@user2.com',
        ]);

        $user3 = User::factory()->create([
           'f_name' => 'Test',
           'l_name' => 'User 3',
           'email' => 'test@user3.com'
        ]);

        $vendor = Vendor::factory()->create([
            'user_id' => $user->id
        ]);

        $vendor2 = Vendor::factory()->create([
           'user_id' => $user3->id
        ]);

        $event_categories = ['Birthday', 'Graduation', 'Wedding', 'Picnic', 'Concert', 'Funeral', 'Baby Shower'];
        foreach ($event_categories as $category) {
            Event::factory(3)->create([
                'user_id' => $user2->id,
                'event_type' => $category
            ]);

            Event::factory(2)->create([
                'user_id' => $user->id,
                'event_type' => $category
            ]);
        }

        $events = Event::all();
        foreach ($events as $event) {
            EventTask::factory(5)->create([
                'event_id' => $event->id,
                'person_responsible' => $user2->f_name.' '.$user2->l_name
            ]);
        }

        // \App\Models\User::factory(10)->create();
         $venues = Category::factory()->create(['name' => 'Venues', 'icon' => 'fa-solid fa-map-location', 'image' => 'venue.jpg']);
         $photography = Category::factory()->create(['name' => 'Photographers', 'icon' => 'fas fa-camera', 'image' => 'photographer.jpg']);
         $catering = Category::factory()->create(['name' => 'Catering', 'icon' => 'fas fa-truck', 'image' => 'caterer.jpg']);
         $videogrpahy = Category::factory()->create(['name' => 'Videographers', 'icon' => 'fa-solid fa-camera-movie', 'image' => 'videographer.jpg']);
         $dressing = Category::factory()->create(['name' => 'Dress and Attire', 'icon' => 'fas fa-tshirt', 'image' => 'dress.jpg']);
         $cakes = Category::factory()->create(['name' => 'Cakes', 'icon' => 'fa fa-birthday-cake' , 'image' => 'cake.jpg']);
         $transportation = Category::factory()->create(['name' => 'Transportation and Valet', 'icon' => 'fa fa-bus', 'image' => 'valet.jpg']);
         $ceremonyMusic = Category::factory()->create(['name' => 'Music', 'icon' => 'fa fa-music', 'image' => 'music.jpg']);
         $flowers = Category::factory()->create(['name' => 'Florist and Decor', 'icon' => 'fa fa-snowflake-o', 'image' => 'florist-decor.jpg']);
         $beauty = Category::factory()->create(['name' => 'Beauty, hair and makeup', 'icon' => 'las la-spa', 'image' => 'makeup.jpg']);
         Category::factory()->create(['name' => 'Wedding Planning', 'icon' => 'fa fa-pencil-square-o', 'image' => 'wedding-planning.jpg']);
         $design = Category::factory()->create(['name' => 'Design and Accents', 'icon' => 'fa-solid fa-palette', 'image' => 'design-2.jpg']);
         Category::factory()->create(['name' => 'Wedding dress and accessories', 'icon' => 'fa-regular fa-person-dresse', 'image' => 'wedding-dress.jpg']);
         $entertainment = Category::factory()->create(['name' => 'Entertainment', 'icon' => 'fa-solid fa-clapperboard-play', 'image' => 'entertainment.jpg']);
         $stationery = Category::factory()->create(['name' => 'Stationery', 'icon' => 'fa-solid fa-pen-paintbrush', 'image' => 'stationery.jpg']);
         $menswear = Category::factory()->create(['name' => 'Menswear', 'icon' => 'fa-solid fa-user-tie', 'image' => 'menswear.jpg']);
         $planners = Category::factory()->create(['name' => 'Planners, Toast Master, MC', 'icon' => 'fa-solid fa-user-headset', 'image' => 'event-planner-2.jpg']);
         Category::factory()->create(['name' => 'Rental Supplier', 'icon' => 'fa-brands fa-hire-a-helper', 'image' => 'rentalsupplier.jpg']);
         $security = Category::factory()->create(['name' => 'Security', 'icon' => 'fa-solid fa-shield-keyhole', 'image' => 'security.jpg']);
         $hotel = Category::factory()->create(['name' => 'Hotel and Accomodation', 'icon' => 'fa-solid fa-bed', 'image' => 'hotel.jpg']);
         $marketing = Category::factory()->create(['name' => 'Marketing and Promotions', 'icon' => 'fa-solid fa-bullhorn', 'image' => 'marketing.jpg']);
         $ushers = Category::factory()->create(['name' => 'Ushers', 'icon' => 'fa-solid fa-id-card-clip', 'image' => 'ushers.jpg']);
         $sommelier = Category::factory()->create(['name' => 'Sommelier', 'icon' => 'fa-solid fa-wine-glass', 'image' => 'sommilier.jpg']);
         $balloons = Category::factory()->create(['name' => 'Balloons', 'icon' => 'fa-solid fa-balloon', 'image' => 'balloons.jpg']);
         $audiovisual = Category::factory()->create(['name' => 'Audiovisual', 'icon' => 'fa-solidid fa-photo-film-music', 'image' => 'audiovisual.jpg']);
         $eventplanner = Category::factory()->create(['name' => 'Event Planner', 'icon' => 'fa-solid fa-calendar-lines-pen', 'image' => 'event-planner-3.jpg']);
         $chefs = Category::factory()->create(['name' => 'Chef', 'icon' => 'fa-solid fa-user-chef', 'image' => 'chefs.jpg']);
         $insurance = Category::factory()->create(['name' => 'Event Insurance', 'icon' => 'fa-solid fa-hand-holding-dollar', 'image' => 'event-insurance.jpg']);
         Category::factory()->create(['name' => 'Other Services', 'icon' => 'fa fa-star-o', 'image' => 'other.jpg']);

        Service::factory(4)->create([
            'vendor_id' => $vendor->id,
            'category_id' => $photography->id
        ]);

        Service::factory(2)->create([
         'vendor_id' => $vendor->id,
         'category_id' => $eventplanner->id
         ]);

         Service::factory(2)->create([
            'vendor_id' => $vendor->id,
            'category_id' => $ushers->id
        ]);

        Service::factory(5)->create([
         'vendor_id' => $vendor->id,
         'category_id' => $marketing->id
         ]);

         Service::factory(4)->create([
            'vendor_id' => $vendor->id,
            'category_id' => $insurance->id
        ]);

        Service::factory(3)->create([
         'vendor_id' => $vendor->id,
         'category_id' => $audiovisual->id
         ]);

         Service::factory(2)->create([
            'vendor_id' => $vendor->id,
            'category_id' => $menswear->id
        ]);

        Service::factory()->create([
            'vendor_id' => $vendor->id,
            'category_id' => $venues->id
        ]);

        Service::factory()->create([
            'vendor_id' => $vendor->id,
            'category_id' => $catering->id
        ]);

        Service::factory()->create([
            'vendor_id' => $vendor->id,
            'category_id' => $videogrpahy->id
        ]);

        Service::factory()->create([
            'vendor_id' => $vendor->id,
            'category_id' => $dressing->id
        ]);

        Service::factory()->create([
         'vendor_id' => $vendor->id,
         'category_id' => $beauty->id
        ]);

      Service::factory()->create([
         'vendor_id' => $vendor->id,
         'category_id' => $entertainment->id
      ]);

      Service::factory()->create([
         'vendor_id' => $vendor->id,
         'category_id' => $flowers->id
      ]);

        Service::factory()->create([
           'vendor_id' => $vendor2->id,
           'category_id' => $cakes->id
        ]);

      Service::factory()->create([
         'vendor_id' => $vendor2->id,
         'category_id' => $ceremonyMusic->id
      ]);

      Service::factory()->create([
         'vendor_id' => $vendor2->id,
         'category_id' => $ceremonyMusic->id
      ]);

      Service::factory()->create([
         'vendor_id' => $vendor2->id,
         'category_id' => $hotel->id
      ]);

      Service::factory()->create([
         'vendor_id' => $vendor2->id,
         'category_id' => $planners->id
      ]);

      Service::factory()->create([
         'vendor_id' => $vendor2->id,
         'category_id' => $sommelier->id
      ]);

      Service::factory()->create([
         'vendor_id' => $vendor2->id,
         'category_id' => $chefs->id
      ]);

      Service::factory()->create([
         'vendor_id' => $vendor2->id,
         'category_id' => $balloons->id
      ]);

      Service::factory()->create([
         'vendor_id' => $vendor2->id,
         'category_id' => $security->id
      ]);
    }
}
