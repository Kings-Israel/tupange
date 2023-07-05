<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Category::factory()->create(['name' => 'Venues', 'icon' => 'las la-map-marked', 'image' => 'venue.jpg']);
      Category::factory()->create(['name' => 'Photographers', 'icon' => 'fas fa-camera', 'image' => 'photographer.jpg']);
      Category::factory()->create(['name' => 'Catering', 'icon' => 'fas fa-truck', 'image' => 'caterer.jpg']);
      Category::factory()->create(['name' => 'Videographers', 'icon' => 'fas fa-video', 'image' => 'videographer.jpg']);
      Category::factory()->create(['name' => 'Dress and Attire', 'icon' => 'fas fa-tshirt', 'image' => 'dress.jpg']);
      Category::factory()->create(['name' => 'Cakes', 'icon' => 'fa fa-birthday-cake' , 'image' => 'cake.jpg']);
      Category::factory()->create(['name' => 'Transportation or Valet', 'icon' => 'las la-shuttle-van', 'image' => 'valet.jpg']);
      Category::factory()->create(['name' => 'Music', 'icon' => 'fa fa-music', 'image' => 'music.jpg']);
      Category::factory()->create(['name' => 'Florist and Decor', 'icon' => 'las la-spa', 'image' => 'florist-decor.jpg']);
      Category::factory()->create(['name' => 'Beauty, hair and makeup', 'icon' => 'las la-pencil-alt', 'image' => 'makeup.jpg']);
      Category::factory()->create(['name' => 'Wedding Planning', 'icon' => 'fa fa-list-ul', 'image' => 'wedding-planning.jpg']);
      Category::factory()->create(['name' => 'Design and Accents', 'icon' => 'las la-palette', 'image' => 'design-2.jpg']);
      Category::factory()->create(['name' => 'Wedding dress and accessories', 'icon' => 'fa fa-female', 'image' => 'wedding-dress.jpg']);
      Category::factory()->create(['name' => 'Entertainment', 'icon' => 'fa fa-film', 'image' => 'entertainment.jpg']);
      Category::factory()->create(['name' => 'Stationery', 'icon' => 'fa fa-paint-brush', 'image' => 'stationery.jpg']);
      Category::factory()->create(['name' => 'Menswear', 'icon' => 'las la-user-tie', 'image' => 'menswear.jpg']);
      Category::factory()->create(['name' => 'Planners, Toast Master, MC', 'icon' => 'fa fa-microphone', 'image' => 'event-planner-2.jpg']);
      Category::factory()->create(['name' => 'Rental Supplier', 'icon' => 'fa fa-gears', 'image' => 'rentalsupplier.jpg']);
      Category::factory()->create(['name' => 'Security', 'icon' => 'fa fa-shield', 'image' => 'security.jpg']);
      Category::factory()->create(['name' => 'Hotel and Accomodation', 'icon' => 'fa fa-bed', 'image' => 'hotel.jpg']);
      Category::factory()->create(['name' => 'Marketing and Promotions', 'icon' => 'fa fa-bullhorn', 'image' => 'marketing.jpg']);
      Category::factory()->create(['name' => 'Ushers', 'icon' => 'fa fa-users', 'image' => 'ushers.jpg']);
      Category::factory()->create(['name' => 'Sommelier', 'icon' => 'las la-wine-glass', 'image' => 'sommilier.jpg']);
      Category::factory()->create(['name' => 'Balloons', 'icon' => 'fa fa-map-marker', 'image' => 'balloons.jpg']);
      Category::factory()->create(['name' => 'Audiovisual', 'icon' => 'fa fa-tv', 'image' => 'audiovisual.jpg']);
      Category::factory()->create(['name' => 'Event Planner', 'icon' => 'fa fa-list-alt', 'image' => 'event-planner-3.jpg']);
      Category::factory()->create(['name' => 'Chef', 'icon' => 'las la-fire', 'image' => 'chefs.jpg']);
      Category::factory()->create(['name' => 'Event Insurance', 'icon' => 'las la-money-bill', 'image' => 'event-insurance.jpg']);
      Category::factory()->create(['name' => 'Other Services', 'icon' => 'fa fa-sun-o', 'image' => 'other.jpg']);
    }
}
