<?php

namespace Database\Factories;

use App\Models\CustomerReview;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerReviewFactory extends Factory
{
   protected $model = CustomerReview::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'review' => $this->faker->paragraph(rand(4,10))
        ];
    }
}
