<?php

namespace App\Models;

use Database\Factories\AlbumFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'genre_id',
    'user_id',
    'title',
    'slug',
    'artist',
    'release_year',
    'cover_url',
    'description',
    'spotify_url',
    'apple_music_url',
    'tracklist',
])]
class Album extends Model
{
    /** @use HasFactory<AlbumFactory> */
    use HasFactory;

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tracklist' => 'array',
            'release_year' => 'integer',
        ];
    }

    /**
     * Get the genre that the album belongs to.
     *
     * @return BelongsTo<Genre, $this>
     */
    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    /**
     * Get the user who submitted the album.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all reviews for the album.
     *
     * @return HasMany<Review, $this>
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    /**
     * Calculate the average rating.
     */
    public function averageRating(): float
    {
        $avg = $this->reviews()->avg('rating');

        return $avg ? round((float) $avg, 1) : 0.0;
    }

    /**
     * Count total reviews.
     */
    public function reviewsCount(): int
    {
        return $this->reviews()->count();
    }

    /**
     * Rating distribution breakdown (1-5 stars).
     *
     * @return array<int, array{count: int, percentage: int}>
     */
    public function ratingDistribution(): array
    {
        $total = $this->reviewsCount();
        $counts = $this->reviews()
            ->selectRaw('rating, count(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->all();

        $distribution = [];
        for ($star = 5; $star >= 1; $star--) {
            $count = (int) ($counts[$star] ?? 0);
            $distribution[$star] = [
                'count' => $count,
                'percentage' => $total > 0 ? (int) round(($count / $total) * 100) : 0,
            ];
        }

        return $distribution;
    }
}
