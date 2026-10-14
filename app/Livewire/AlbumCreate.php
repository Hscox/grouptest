<?php

namespace App\Livewire;

use App\Models\Album;
use App\Models\Genre;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
class AlbumCreate extends Component
{
    #[Validate('required|string|min:2|max:150')]
    public string $title = '';

    #[Validate('required|string|min:2|max:150')]
    public string $artist = '';

    #[Validate('required|integer|min:1900|max:2030')]
    public int $release_year = 2024;

    #[Validate('required|exists:genres,id')]
    public ?int $genre_id = null;

    #[Validate('nullable|url|max:500')]
    public ?string $cover_url = '';

    #[Validate('nullable|string|max:3000')]
    public ?string $description = '';

    #[Validate('nullable|url|max:255')]
    public ?string $spotify_url = '';

    #[Validate('nullable|url|max:255')]
    public ?string $apple_music_url = '';

    public string $tracklist_input = '';

    /**
     * Mount component and set default year.
     */
    public function mount(): void
    {
        $this->release_year = (int) date('Y');
        $defaultGenre = Genre::first();
        if ($defaultGenre) {
            $this->genre_id = $defaultGenre->id;
        }
    }

    /**
     * Submit and create the new album.
     */
    public function save(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));

            return;
        }

        $this->validate();

        // Parse tracklist lines
        $tracks = [];
        if (filled($this->tracklist_input)) {
            $lines = explode("\n", $this->tracklist_input);
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if (filled($trimmed)) {
                    $tracks[] = $trimmed;
                }
            }
        }

        // Generate unique slug
        $baseSlug = Str::slug($this->title.'-'.$this->artist);
        if (empty($baseSlug)) {
            $baseSlug = 'album-'.Str::random(6);
        }

        $slug = $baseSlug;
        $counter = 1;
        while (Album::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }

        $defaultCover = 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800&auto=format&fit=crop&q=80';

        $album = Album::create([
            'genre_id' => $this->genre_id,
            'user_id' => Auth::id(),
            'title' => trim($this->title),
            'slug' => $slug,
            'artist' => trim($this->artist),
            'release_year' => $this->release_year,
            'cover_url' => filled($this->cover_url) ? trim($this->cover_url) : $defaultCover,
            'description' => filled($this->description) ? trim($this->description) : null,
            'spotify_url' => filled($this->spotify_url) ? trim($this->spotify_url) : null,
            'apple_music_url' => filled($this->apple_music_url) ? trim($this->apple_music_url) : null,
            'tracklist' => count($tracks) > 0 ? $tracks : null,
        ]);

        session()->flash('status', 'Album added successfully! You can now write the first review.');

        $this->redirect(route('albums.show', $album));
    }

    /**
     * Render the form view.
     */
    public function render(): View
    {
        return view('livewire.album-create', [
            'genres' => Genre::orderBy('name')->get(),
        ]);
    }
}
