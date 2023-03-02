<?php

namespace Database\Factories;

use App\Event;
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
        return [
            'user_id' => 1,
            'title' => $this->faker->unique()->sentence(2),
            'status' => 'publish',
            'channel_name' => $this->faker->unique()->sentence(1),
            'start_date_time' => now(),
        ];
    }
}
