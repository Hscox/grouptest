<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Genre;
use App\Models\Review;
use App\Models\User;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Show the application landing page.
     */
    public function index(): View
    {
        $topAlbums = Album::query()
            ->with(['genre', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->take(6)
            ->get();

        $recentReviews = Review::query()
            ->with(['user', 'album.genre', 'likes'])
            ->latest()
            ->take(6)
            ->get();

        $genres = Genre::query()
            ->withCount('albums')
            ->orderBy('name')
            ->get();

        $stats = [
            'total_albums' => Album::count(),
            'total_reviews' => Review::count(),
            'total_critics' => User::count(),
            'average_rating' => round((float) (Review::avg('rating') ?: 0), 1),
        ];

        return view('welcome', compact('topAlbums', 'recentReviews', 'genres', 'stats'));
    }
}
