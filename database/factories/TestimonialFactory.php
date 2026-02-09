<?php

namespace Database\Factories;

use App\Models\Creator;
use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Testimonial>
 */
class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'creator_id' => Creator::factory(),
            'author_name' => fake()->name(),
            'author_email' => fake()->safeEmail(),
            'author_title' => fake()->jobTitle(),
            'content' => fake()->paragraph(),
            'rating' => fake()->numberBetween(1, 5),
            'status' => Testimonial::STATUS_PENDING,
            'source' => 'direct',
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Testimonial::STATUS_APPROVED,
            'approved_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Testimonial::STATUS_REJECTED,
        ]);
    }
}
