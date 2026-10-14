<?php

namespace Database\Factories;

use App\Models\Album;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'album_id' => Album::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'title' => fake()->sentence(4),
            'content' => fake()->paragraphs(2, true),
            'favorite_track' => fake()->words(2, true),
            'is_recommended' => fake()->boolean(80),
            'likes_count' => fake()->numberBetween(0, 20),
        ];
    }
}
