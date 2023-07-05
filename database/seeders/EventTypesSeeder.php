<?php

namespace Database\Seeders;

use App\Models\EventTypes;
use Illuminate\Database\Seeder;

class EventTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $event_types = [
         'Birthday' => 'Birthday',
         'Wedding' => 'Wedding',
         'Graduation' => 'Graduation',
         'Baby Shower' => 'Baby Shower',
         'Bridal Party' => 'Bridal Party',
         'Bachelor Party' => 'Bachelor Party',
         'Bachellorette Party' => 'Bachellorette Party',
         'Funeral' => 'Funeral',
         'Family Gathering' => 'Family Gathering',
         'Retirement Party' => 'Retirement Party',
         'Retirement Party' => 'Retirement Party',
      ];

      foreach ($event_types as $key => $type) {
         EventTypes::create([
            'name' => $type
         ]);
      }
    }
}
