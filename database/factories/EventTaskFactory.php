<?php

namespace Database\Factories;

use App\Models\EventTask;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventTaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EventTask::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $notify_due = ['Daily', 'Never', 'Weekly', 'Monthly'];
        $status = ['Open', 'In Progress', 'Complete', 'Closed'];
        return [
            'task' => ucfirst($this->faker->word()),
            'notify_due' => $notify_due[rand(0,3)],
            'date_due' => now()->addWeek(),
            'status' => $status[rand(0,3)],
        ];
    }
}
