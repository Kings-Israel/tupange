<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vendor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $phone_numbers = $this->faker->randomElement($array = array ('0707234543', '0707594584', '0796948543', '0707656543'));
        return [
            'company_name' => $this->faker->words(2, true),
            'company_description' => $this->faker->paragraph(8),
            'company_phone_number' => $phone_numbers,
            'location' => "(-1.2719836905965618, 36.799666005906055)",
            'company_email' => $this->faker->email(),
            'company_logo' => 'sample/logo.png',
            'status' => 'Active'
        ];
    }
}
