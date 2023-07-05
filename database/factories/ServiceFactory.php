<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Service::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $phone_numbers = $this->faker->randomElement($array = array ('0707234543', '0707594584', '0796948543', '0707656543'));
        return [
            'service_title' => ucfirst($this->faker->words(2, true)),
            'service_description' => $this->faker->paragraph(10),
            'service_contact_email' => $this->faker->email(),
            'service_contact_phone_number' => $phone_numbers,
            'service_image' => 'sample/service'.rand(1,4).'.jpg',
            'service_location_lat' => -1.270104,
            'service_location_long' => 36.80814,
            'service_location' => 'Westlands, Kenya',
        ];
    }
}
