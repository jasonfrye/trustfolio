<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestimonialRequest>
 */
class TestimonialRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'recipient_email' => fake()->safeEmail(),
            'recipient_name' => fake()->name(),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
