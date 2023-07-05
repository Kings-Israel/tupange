<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Role::factory()->create([
         'name' => 'Committee'
      ]);
      Role::factory()->create([
         'name' => 'Tasks'
      ]);
    }
}
