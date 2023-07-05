<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceStatus;

class ServiceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      ServiceStatus::factory()->create(['name' => 'Active']);
      ServiceStatus::factory()->create(['name' => 'Paused']);
      ServiceStatus::factory()->create(['name' => 'Deleted']);
    }
}
