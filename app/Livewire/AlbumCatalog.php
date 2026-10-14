<?php

namespace App\Livewire;

use App\Models\Album;
use App\Models\Genre;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AlbumCatalog extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $genre = '';

    #[Url(history: true)]
    public string $sort = 'highest_rated';

    /**
     * Reset pagination when search query updates.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when genre filter updates.
     */
    public function updatedGenre(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when sort option updates.
     */
    public function updatedSort(): void
    {
        $this->resetPage();
    }

    /**
     * Reset all active filters.
     */
    public function resetFilters(): void
    {
        $this->reset(['search', 'genre', 'sort']);
        $this->resetPage();
    }

    /**
     * Render the albums catalog view.
     */
    public function render(): View
    {
        $query = Album::query()
            ->with(['genre', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        if (filled($this->search)) {
            $searchTerm = '%'.trim($this->search).'%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                    ->orWhere('artist', 'like', $searchTerm);
            });
        }

        if (filled($this->genre)) {
            $query->whereHas('genre', function ($q) {
                $q->where('slug', $this->genre);
            });
        }

        match ($this->sort) {
            'highest_rated' => $query->orderByDesc('reviews_avg_rating')->orderByDesc('reviews_count'),
            'most_reviewed' => $query->orderByDesc('reviews_count')->orderByDesc('reviews_avg_rating'),
            'newest_release' => $query->orderByDesc('release_year'),
            'oldest_release' => $query->orderBy('release_year'),
            'title_asc' => $query->orderBy('title'),
            default => $query->latest(),
        };

        $albums = $query->paginate(9);
        $genres = Genre::query()->withCount('albums')->orderBy('name')->get();

        return view('livewire.album-catalog', [
            'albums' => $albums,
            'genres' => $genres,
        ]);
    }
}
