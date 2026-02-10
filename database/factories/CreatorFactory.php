<?php

namespace Database\Factories;

use App\Models\Creator;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CreatorFactory extends Factory
{
    protected $model = Creator::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'display_name' => $this->faker->name(),
            'collection_url' => $this->faker->slug(),
            'website' => $this->faker->url(),
            'avatar_url' => $this->faker->imageUrl(),
            'widget_theme' => $this->faker->randomElement(['light', 'dark', 'custom']),
            'primary_color' => $this->faker->hexColor(),
            'font_family' => $this->faker->randomElement(['system-ui', 'Georgia', 'Times New Roman']),
            'show_branding' => $this->faker->boolean(80),
            'plan' => 'free',
            'subscription_status' => 'inactive',
        ];
    }

    public function pro(): self
    {
        return $this->state([
            'plan' => 'pro',
            'subscription_status' => 'active',
        ]);
    }

    public function business(): self
    {
        return $this->state([
            'plan' => 'business',
            'subscription_status' => 'active',
        ]);
    }
}
