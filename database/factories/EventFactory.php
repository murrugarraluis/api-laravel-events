<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->realText(100),
            'slug' => $this->faker->slug,
            'content' => $this->faker->text,
            'description' => $this->faker->text,
            'poster' => $this->faker->imageUrl,
            'date' => $this->faker->date(),
            'time' => $this->faker->time(),
            'category_id' => $this->faker->numberBetween(1, 2),
        ];
    }
}
