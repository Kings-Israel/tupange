<?php

namespace Database\Factories;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $settings = [];

        $settings['events_guests_expected'] = rand(0, 50);
        $settings['events_guests_max'] = rand(50, 200);

        return [
            'event_name' => ucfirst($this->faker->word()),
            'event_location_lat' => -1.2719389992194,
            'event_location_long' => 36.79936142588,
            'event_location' => "Westlands, Nairobi",
            'event_start_date' => now(),
            'event_end_date' => now()->addMonth(),
            'event_description' => $this->faker->paragraph(3),
            'event_settings' => json_encode($settings),
            'event_poster' => 'sample/event'.rand(1,4).'.jpg',
        ];
    }
}
