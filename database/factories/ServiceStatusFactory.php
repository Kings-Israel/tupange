<?php

namespace Database\Factories;

use App\Models\ServiceStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceStatusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ServiceStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word()
        ];
    }
}
