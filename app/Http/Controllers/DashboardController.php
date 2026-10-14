<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\ReviewLike;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the authenticated user's dashboard.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $reviews = $user->reviews()
            ->with(['album.genre'])
            ->get();

        $reviewedAlbumIds = $reviews->pluck('album_id')->all();

        $albumsToReview = Album::query()
            ->with('genre')
            ->whereNotIn('id', $reviewedAlbumIds)
            ->latest()
            ->take(4)
            ->get();

        $reviewIds = $reviews->pluck('id')->all();
        $totalLikesReceived = count($reviewIds) > 0
            ? ReviewLike::whereIn('review_id', $reviewIds)->count()
            : 0;

        $stats = [
            'reviews_count' => $reviews->count(),
            'average_rating' => $reviews->count() > 0 ? round((float) $reviews->avg('rating'), 1) : 0.0,
            'likes_received' => $totalLikesReceived,
        ];

        return view('dashboard', compact('user', 'reviews', 'albumsToReview', 'stats'));
    }
}
