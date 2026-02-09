<?php

namespace Database\Factories;

use App\Models\Creator;
use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

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
            'status' => Review::STATUS_PENDING,
            'source' => 'direct',
            'is_private_feedback' => false,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Review::STATUS_APPROVED,
            'approved_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Review::STATUS_REJECTED,
        ]);
    }

    public function privateFeedback(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_private_feedback' => true,
        ]);
    }
}
