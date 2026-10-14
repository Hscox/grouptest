<?php

namespace Database\Factories;

use App\Models\Album;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Album>
 */
class AlbumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'genre_id' => Genre::factory(),
            'user_id' => User::factory(),
            'title' => rtrim($title, '.'),
            'slug' => Str::slug($title).'-'.fake()->unique()->randomNumber(5),
            'artist' => fake()->name(),
            'release_year' => fake()->numberBetween(1965, 2026),
            'cover_url' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=600&auto=format&fit=crop&q=80',
            'description' => fake()->paragraphs(2, true),
            'spotify_url' => 'https://open.spotify.com',
            'apple_music_url' => 'https://music.apple.com',
            'tracklist' => [
                'Track 1 - Prelude',
                'Track 2 - Rising Sun',
                'Track 3 - Echoes in the Dark',
                'Track 4 - Midnight Melody',
                'Track 5 - Outro',
            ],
        ];
    }
}
