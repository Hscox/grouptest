<div class="mx-auto max-w-3xl space-y-8 pb-16">
    <!-- Header -->
    <div>
        <div class="flex items-center gap-2 text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-2">
            <a href="{{ route('home') }}" class="hover:underline">Home</a>
            <span>/</span>
            <a href="{{ route('albums.index') }}" class="hover:underline">Albums</a>
            <span>/</span>
            <span class="text-zinc-800 dark:text-zinc-200">Submit Album</span>
        </div>
        <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Submit an Album</h1>
        <p class="text-sm text-zinc-500 dark:text-zinc-400">
            Add a record to the community catalog so listeners can rate, review, and discuss it.
        </p>
    </div>

    <!-- Form Card -->
    <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 md:p-8">
        <form wire:submit="save" class="space-y-6">
            <!-- Title & Artist -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Album Title *
                    </label>
                    <input
                        type="text"
                        wire:model="title"
                        placeholder="e.g. In Rainbows"
                        class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-violet-500 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-violet-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                    />
                    @error('title') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Artist / Band *
                    </label>
                    <input
                        type="text"
                        wire:model="artist"
                        placeholder="e.g. Radiohead"
                        class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-violet-500 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-violet-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                    />
                    @error('artist') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Release Year & Genre -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Release Year *
                    </label>
                    <input
                        type="number"
                        wire:model="release_year"
                        min="1900"
                        max="2030"
                        class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-violet-500 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-violet-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                    />
                    @error('release_year') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Genre *
                    </label>
                    <select
                        wire:model="genre_id"
                        class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-3.5 py-2.5 text-sm text-zinc-900 focus:border-violet-500 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-violet-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                    >
                        <option value="">Select a Genre</option>
                        @foreach($genres as $g)
                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                        @endforeach
                    </select>
                    @error('genre_id') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Cover Art URL -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1.5">
                    Cover Artwork URL (Optional)
                </label>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <input
                        type="url"
                        wire:model.live.debounce.500ms="cover_url"
                        placeholder="https://example.com/cover.jpg (or leave empty for default)"
                        class="flex-1 rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-violet-500 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-violet-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                    />
                    @if(filled($cover_url))
                        <div class="size-12 shrink-0 overflow-hidden rounded-lg bg-zinc-100 border border-zinc-200 dark:border-zinc-700">
                            <img src="{{ $cover_url }}" alt="Preview" class="h-full w-full object-cover" onerror="this.src='https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=600&auto=format&fit=crop&q=80'" />
                        </div>
                    @endif
                </div>
                @error('cover_url') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
            </div>

            <!-- Description / Background -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1.5">
                    Album Story / Background (Optional)
                </label>
                <textarea
                    wire:model="description"
                    rows="3"
                    placeholder="Brief background about the release, production context, themes, or awards..."
                    class="w-full rounded-xl border border-zinc-200 bg-zinc-50 p-4 text-sm text-zinc-900 placeholder-zinc-400 focus:border-violet-500 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-violet-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                ></textarea>
                @error('description') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
            </div>

            <!-- Streaming Links -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Spotify Link (Optional)
                    </label>
                    <input
                        type="url"
                        wire:model="spotify_url"
                        placeholder="https://open.spotify.com/album/..."
                        class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-violet-500 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-violet-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                    />
                    @error('spotify_url') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1.5">
                        Apple Music Link (Optional)
                    </label>
                    <input
                        type="url"
                        wire:model="apple_music_url"
                        placeholder="https://music.apple.com/..."
                        class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-violet-500 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-violet-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                    />
                    @error('apple_music_url') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Tracklist Input -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300 mb-1.5">
                    Tracklist (Optional)
                </label>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mb-2">
                    Enter each song title on a new line.
                </p>
                <textarea
                    wire:model="tracklist_input"
                    rows="6"
                    placeholder="1. 15 Step&#10;2. Bodysnatchers&#10;3. Nude&#10;4. Weird Fishes / Arpeggi"
                    class="w-full font-mono rounded-xl border border-zinc-200 bg-zinc-50 p-4 text-xs text-zinc-900 placeholder-zinc-400 focus:border-violet-500 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-violet-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                ></textarea>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                <flux:button :href="route('albums.index')" variant="ghost" wire:navigate>
                    Cancel
                </flux:button>
                <button
                    type="submit"
                    class="cursor-pointer inline-flex items-center justify-center rounded-xl bg-violet-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-violet-500"
                >
                    Create Album
                </button>
            </div>
        </form>
    </div>
</div>
