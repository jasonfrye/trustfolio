<?php

namespace Database\Factories;

use App\Models\WidgetSetting;
use App\Models\Creator;
use Illuminate\Database\Eloquent\Factories\Factory;

class WidgetSettingFactory extends Factory
{
    protected $model = WidgetSetting::class;

    public function definition(): array
    {
        return [
            'creator_id' => Creator::factory(),
            'theme' => 'light', // Default theme
            'primary_color' => $this->faker->hexColor(),
            'background_color' => $this->faker->hexColor(),
            'text_color' => $this->faker->hexColor(),
            'border_radius' => $this->faker->numberBetween(0, 16),
            'layout' => $this->faker->randomElement(['cards', 'list', 'grid']),
            'limit' => $this->faker->numberBetween(3, 20),
            'show_ratings' => $this->faker->boolean(80),
            'show_avatars' => $this->faker->boolean(60),
            'show_dates' => $this->faker->boolean(70),
            'minimum_rating' => $this->faker->numberBetween(1, 5),
            'sort_order' => $this->faker->randomElement(['recent', 'random', 'highest_rated']),
            'show_branding' => $this->faker->boolean(80),
        ];
    }
}
