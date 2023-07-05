<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CustomerReview;
use Illuminate\Database\Seeder;

class CustomerReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      CustomerReview::factory()->create([
         'user_id' => User::factory()->create()->id,
      ]);

      CustomerReview::create()->create([
         'user_id' => User::factory()->create()->id,
      ]);

      CustomerReview::factory()->create([
         'user_id' => User::factory()->create()->id,
      ]);

      CustomerReview::factory()->create([
         'user_id' => User::factory()->create()->id,
      ]);

      CustomerReview::factory()->create([
         'user_id' => User::factory()->create()->id,
      ]);

      CustomerReview::factory()->create([
         'user_id' => User::factory()->create()->id,
      ]);
    }
}
