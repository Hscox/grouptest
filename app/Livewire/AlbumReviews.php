<?php

namespace App\Livewire;

use App\Models\Album;
use App\Models\Review;
use App\Models\ReviewLike;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AlbumReviews extends Component
{
    public Album $album;

    #[Validate('required|integer|between:1,5')]
    public int $rating = 5;

    #[Validate('required|string|min:3|max:120')]
    public string $title = '';

    #[Validate('required|string|min:10|max:2500')]
    public string $content = '';

    #[Validate('nullable|string|max:100')]
    public ?string $favorite_track = '';

    #[Validate('boolean')]
    public bool $is_recommended = true;

    public bool $isEditing = false;

    public ?int $editingReviewId = null;

    public ?string $statusMessage = null;

    /**
     * Mount the component.
     */
    public function mount(Album $album): void
    {
        $this->album = $album;
    }

    /**
     * Set the rating via star clicks.
     */
    public function setRating(int $stars): void
    {
        $this->rating = max(1, min(5, $stars));
    }

    /**
     * Populate form to edit the current user's review.
     */
    public function editReview(int $reviewId): void
    {
        $review = Review::where('id', $reviewId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->editingReviewId = $review->id;
        $this->rating = $review->rating;
        $this->title = $review->title;
        $this->content = $review->content;
        $this->favorite_track = $review->favorite_track ?? '';
        $this->is_recommended = (bool) $review->is_recommended;
        $this->isEditing = true;
    }

    /**
     * Cancel editing.
     */
    public function cancelEditing(): void
    {
        $this->reset(['isEditing', 'editingReviewId', 'title', 'content', 'favorite_track']);
        $this->rating = 5;
        $this->is_recommended = true;
    }

    /**
     * Save or update the review.
     */
    public function saveReview(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));

            return;
        }

        $this->validate();

        $userId = Auth::id();

        if ($this->isEditing && $this->editingReviewId) {
            $review = Review::where('id', $this->editingReviewId)
                ->where('user_id', $userId)
                ->firstOrFail();

            $review->update([
                'rating' => $this->rating,
                'title' => $this->title,
                'content' => $this->content,
                'favorite_track' => filled($this->favorite_track) ? trim($this->favorite_track) : null,
                'is_recommended' => $this->is_recommended,
            ]);

            $this->statusMessage = 'Your review was successfully updated!';
            $this->cancelEditing();
        } else {
            // Check if user already reviewed
            $existing = Review::where('album_id', $this->album->id)
                ->where('user_id', $userId)
                ->first();

            if ($existing) {
                $existing->update([
                    'rating' => $this->rating,
                    'title' => $this->title,
                    'content' => $this->content,
                    'favorite_track' => filled($this->favorite_track) ? trim($this->favorite_track) : null,
                    'is_recommended' => $this->is_recommended,
                ]);

                $this->statusMessage = 'Your review was successfully updated!';
            } else {
                Review::create([
                    'album_id' => $this->album->id,
                    'user_id' => $userId,
                    'rating' => $this->rating,
                    'title' => $this->title,
                    'content' => $this->content,
                    'favorite_track' => filled($this->favorite_track) ? trim($this->favorite_track) : null,
                    'is_recommended' => $this->is_recommended,
                    'likes_count' => 0,
                ]);

                $this->statusMessage = 'Thank you! Your review has been published.';
            }

            $this->reset(['title', 'content', 'favorite_track']);
            $this->rating = 5;
            $this->is_recommended = true;
        }

        $this->album->refresh();
    }

    /**
     * Delete the current user's review.
     */
    public function deleteReview(int $reviewId): void
    {
        if (! Auth::check()) {
            return;
        }

        $review = Review::where('id', $reviewId)
            ->where('user_id', Auth::id())
            ->first();

        if ($review) {
            $review->delete();
            $this->statusMessage = 'Your review has been removed.';
            $this->cancelEditing();
            $this->album->refresh();
        }
    }

    /**
     * Toggle like/helpful on a review.
     */
    public function toggleLike(int $reviewId): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));

            return;
        }

        $review = Review::findOrFail($reviewId);
        $userId = Auth::id();

        $like = ReviewLike::where('user_id', $userId)
            ->where('review_id', $review->id)
            ->first();

        if ($like) {
            $like->delete();
            $review->decrement('likes_count');
        } else {
            ReviewLike::create([
                'user_id' => $userId,
                'review_id' => $review->id,
            ]);
            $review->increment('likes_count');
        }

        $this->album->refresh();
    }

    /**
     * Render the reviews section.
     */
    public function render(): View
    {
        $reviews = $this->album->reviews()
            ->with(['user', 'likes'])
            ->latest()
            ->get();

        $currentUserReview = Auth::check()
            ? $reviews->firstWhere('user_id', Auth::id())
            : null;

        return view('livewire.album-reviews', [
            'reviews' => $reviews,
            'currentUserReview' => $currentUserReview,
        ]);
    }
}
