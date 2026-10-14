<div class="space-y-8 pb-16">
    <!-- Header & Action -->
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-white">Explore Albums</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Discover albums, listen to community consensus, and leave your critique.</p>
        </div>

        <div class="flex items-center gap-3">
            @auth
                <flux:button :href="route('albums.create')" variant="primary" icon="plus" wire:navigate>
                    Submit Album
                </flux:button>
            @else
                <flux:button :href="route('login')" variant="primary" icon="plus" wire:navigate>
                    Submit Album
                </flux:button>
            @endauth
        </div>
    </div>

    <!-- Filter & Search Controls -->
    <div class="flex flex-col gap-4 rounded-2xl border border-zinc-200 bg-white p-4 shadow-xs dark:border-zinc-800 dark:bg-zinc-900 md:p-6">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-12">
            <!-- Search bar -->
            <div class="md:col-span-6">
                <div class="relative">
                    <flux:icon name="magnifying-glass" class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-zinc-400" />
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search by title, artist..."
                        class="w-full rounded-xl border border-zinc-200 bg-zinc-50 pl-10 pr-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-violet-500 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-violet-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white dark:focus:bg-zinc-800"
                    />
                    @if(filled($search))
                        <button
                            wire:click="$set('search', '')"
                            class="cursor-pointer absolute right-3 top-1/2 -translate-y-1/2 text-xs text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200"
                        >
                            ✕
                        </button>
                    @endif
                </div>
            </div>

            <!-- Genre Select -->
            <div class="md:col-span-3">
                <select
                    wire:model.live="genre"
                    class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-3.5 py-2.5 text-sm text-zinc-900 focus:border-violet-500 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-violet-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                >
                    <option value="">All Genres ({{ $genres->sum('albums_count') }})</option>
                    @foreach($genres as $g)
                        <option value="{{ $g->slug }}">{{ $g->name }} ({{ $g->albums_count }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Sort Select -->
            <div class="md:col-span-3">
                <select
                    wire:model.live="sort"
                    class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-3.5 py-2.5 text-sm text-zinc-900 focus:border-violet-500 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-violet-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                >
                    <option value="highest_rated">★ Highest Rated</option>
                    <option value="most_reviewed">💬 Most Reviewed</option>
                    <option value="newest_release">📅 Release Year (Newest)</option>
                    <option value="oldest_release">⏳ Release Year (Classic)</option>
                    <option value="latest">✨ Recently Added</option>
                    <option value="title_asc">🔤 Title (A - Z)</option>
                </select>
            </div>
        </div>

        <!-- Genre Pill Tags -->
        <div class="flex flex-wrap items-center gap-1.5 pt-2 border-t border-zinc-100 dark:border-zinc-800">
            <button
                type="button"
                wire:click="$set('genre', '')"
                class="cursor-pointer rounded-full px-3 py-1 text-xs font-medium transition {{ empty($genre) ? 'bg-violet-600 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700' }}"
            >
                All
            </button>
            @foreach($genres as $g)
                <button
                    type="button"
                    wire:click="$set('genre', '{{ $g->slug }}')"
                    class="cursor-pointer rounded-full px-3 py-1 text-xs font-medium transition {{ $genre === $g->slug ? 'bg-violet-600 text-white' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700' }}"
                >
                    {{ $g->name }}
                </button>
            @endforeach

            @if(filled($search) || filled($genre) || $sort !== 'highest_rated')
                <button
                    type="button"
                    wire:click="resetFilters"
                    class="cursor-pointer ml-auto text-xs text-rose-500 hover:text-rose-600 hover:underline font-medium"
                >
                    Reset all filters
                </button>
            @endif
        </div>
    </div>

    <!-- Active Count Display -->
    <div class="flex items-center justify-between text-xs text-zinc-500 dark:text-zinc-400">
        <div>
            Found <span class="font-bold text-zinc-800 dark:text-zinc-200">{{ $albums->total() }}</span> {{ Str::plural('album', $albums->total()) }}
            @if(filled($search))
                for "<span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $search }}</span>"
            @endif
        </div>
    </div>

    <!-- Albums Grid -->
    @if($albums->count() > 0)
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($albums as $album)
                <div class="group flex flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-xs transition hover:shadow-lg dark:border-zinc-800 dark:bg-zinc-900">
                    <!-- Cover Art -->
                    <a href="{{ route('albums.show', $album) }}" class="relative block aspect-square w-full overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                        <img
                            src="{{ $album->cover_url }}"
                            alt="{{ $album->title }}"
                            class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                            loading="lazy"
                        />
                        <!-- Rating Badge -->
                        <div class="absolute top-3 right-3 flex items-center gap-1 rounded-full bg-zinc-900/80 px-2.5 py-1 text-xs font-semibold text-amber-400 backdrop-blur-md">
                            <span>★</span>
                            <span>{{ $album->averageRating() }}</span>
                        </div>
                        <!-- Genre Tag -->
                        <div class="absolute bottom-3 left-3">
                            <span class="rounded-md bg-zinc-900/80 px-2.5 py-1 text-xs font-medium text-zinc-200 backdrop-blur-md">
                                {{ $album->genre->name }}
                            </span>
                        </div>
                    </a>

                    <!-- Details -->
                    <div class="flex flex-1 flex-col justify-between p-5">
                        <div>
                            <h3 class="text-lg font-bold text-zinc-900 line-clamp-1 dark:text-white">
                                <a href="{{ route('albums.show', $album) }}" class="hover:underline">
                                    {{ $album->title }}
                                </a>
                            </h3>
                            <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
                                {{ $album->artist }} &bull; {{ $album->release_year }}
                            </p>

                            @if($album->description)
                                <p class="mt-2 text-xs text-zinc-600 line-clamp-2 dark:text-zinc-400">
                                    {{ $album->description }}
                                </p>
                            @endif
                        </div>

                        <div class="mt-4 flex items-center justify-between border-t border-zinc-100 pt-3 text-xs text-zinc-500 dark:border-zinc-800 dark:text-zinc-400">
                            <span>{{ $album->reviews_count }} {{ Str::plural('review', $album->reviews_count) }}</span>
                            <a href="{{ route('albums.show', $album) }}" class="font-semibold text-violet-600 hover:text-violet-500 dark:text-violet-400">
                                Read / Rate &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $albums->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-zinc-300 p-12 text-center dark:border-zinc-700">
            <div class="flex size-14 items-center justify-center rounded-full bg-zinc-100 text-2xl dark:bg-zinc-800">
                🎵
            </div>
            <h3 class="mt-4 text-lg font-bold text-zinc-900 dark:text-white">No albums found</h3>
            <p class="mt-1 max-w-sm text-sm text-zinc-500 dark:text-zinc-400">
                We couldn't find any albums matching your criteria. Try adjusting your search or filters.
            </p>
            <div class="mt-6 flex gap-3">
                <flux:button wire:click="resetFilters" variant="ghost">
                    Clear Filters
                </flux:button>
                @auth
                    <flux:button :href="route('albums.create')" variant="primary" wire:navigate>
                        + Submit This Album
                    </flux:button>
                @endauth
            </div>
        </div>
    @endif
</div>
