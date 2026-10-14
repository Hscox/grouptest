<x-layouts::app :title="__('My Critic Dashboard')">
    <div class="space-y-8 pb-16">
        <!-- Welcome Banner -->
        <div class="relative overflow-hidden rounded-3xl border border-zinc-200 bg-linear-to-r from-violet-600 to-indigo-700 p-6 text-white shadow-md sm:p-8 dark:border-zinc-800">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <flux:avatar :name="$user->name" size="lg" class="border-2 border-white/40 shadow-sm" />
                    <div>
                        <div class="text-xs font-semibold uppercase tracking-wider text-violet-200">Critic Profile</div>
                        <h1 class="text-2xl font-extrabold sm:text-3xl text-white">{{ $user->name }}</h1>
                        <p class="text-xs text-violet-100">Member since {{ $user->created_at->format('M Y') }} &bull; {{ $user->email }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <flux:button :href="route('albums.create')" variant="primary" class="bg-white text-violet-900 hover:bg-zinc-100 font-semibold shadow-xs" wire:navigate>
                        + Submit Album
                    </flux:button>
                    <flux:button :href="route('albums.index')" variant="ghost" class="text-white hover:bg-white/10" wire:navigate>
                        Explore Music
                    </flux:button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="flex items-center gap-4 rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex size-12 items-center justify-center rounded-xl bg-violet-100 text-2xl text-violet-600 dark:bg-violet-900/40 dark:text-violet-400">
                    ✍️
                </div>
                <div>
                    <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['reviews_count'] }}</div>
                    <div class="text-xs text-zinc-500 dark:text-zinc-400">Reviews Written</div>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex size-12 items-center justify-center rounded-xl bg-amber-100 text-2xl text-amber-600 dark:bg-amber-900/40 dark:text-amber-400">
                    ★
                </div>
                <div>
                    <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['average_rating'] }} / 5.0</div>
                    <div class="text-xs text-zinc-500 dark:text-zinc-400">Average Rating Given</div>
                </div>
            </div>

            <div class="flex items-center gap-4 rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                <div class="flex size-12 items-center justify-center rounded-xl bg-pink-100 text-2xl text-pink-600 dark:bg-pink-900/40 dark:text-pink-400">
                    👍
                </div>
                <div>
                    <div class="text-2xl font-bold text-zinc-900 dark:text-white">{{ $stats['likes_received'] }}</div>
                    <div class="text-xs text-zinc-500 dark:text-zinc-400">Helpful Upvotes Received</div>
                </div>
            </div>
        </div>

        <!-- User's Reviews Section -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Your Published Reviews</h2>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">All album critiques written by you</p>
                </div>
            </div>

            @if($reviews->count() > 0)
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    @foreach($reviews as $review)
                        <div class="flex flex-col justify-between rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                            <div>
                                <div class="flex items-center gap-3 border-b border-zinc-100 pb-3 dark:border-zinc-800">
                                    <img
                                        src="{{ $review->album->cover_url }}"
                                        alt="{{ $review->album->title }}"
                                        class="size-12 rounded-lg object-cover"
                                    />
                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('albums.show', $review->album) }}" class="truncate block font-bold text-zinc-900 hover:underline dark:text-white">
                                            {{ $review->album->title }}
                                        </a>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $review->album->artist }} &bull; {{ $review->album->genre->name }}</p>
                                    </div>
                                    <div class="flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800 dark:bg-amber-900/30 dark:text-amber-400">
                                        ★ {{ $review->rating }}.0
                                    </div>
                                </div>

                                <div class="mt-3 space-y-1">
                                    <h4 class="font-semibold text-zinc-900 dark:text-white">{{ $review->title }}</h4>
                                    <p class="text-xs text-zinc-600 line-clamp-3 leading-relaxed dark:text-zinc-300">
                                        {{ $review->content }}
                                    </p>
                                </div>

                                @if($review->favorite_track)
                                    <div class="mt-2.5 inline-flex items-center gap-1 rounded-md bg-zinc-100 px-2 py-0.5 text-xs text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                        <span>🎵</span>
                                        <span class="truncate">{{ $review->favorite_track }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4 flex items-center justify-between border-t border-zinc-100 pt-3 text-xs text-zinc-500 dark:border-zinc-800 dark:text-zinc-400">
                                <span>{{ $review->created_at->format('M j, Y') }}</span>
                                <a
                                    href="{{ route('albums.show', $review->album) }}"
                                    class="font-semibold text-violet-600 hover:text-violet-500 dark:text-violet-400"
                                >
                                    Manage Review &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-zinc-300 p-8 text-center dark:border-zinc-700">
                    <div class="text-3xl">📝</div>
                    <h3 class="mt-2 font-bold text-zinc-900 dark:text-white">You haven't written any reviews yet</h3>
                    <p class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">Choose an album below or explore the catalog to share your opinion.</p>
                    <div class="mt-4">
                        <flux:button :href="route('albums.index')" variant="primary" size="sm" wire:navigate>
                            Explore Albums
                        </flux:button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Albums You Haven't Reviewed Yet -->
        @if($albumsToReview->count() > 0)
            <div class="space-y-4 pt-4">
                <div>
                    <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Albums Waiting for Your Review</h2>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400">Popular releases from the community that you haven't reviewed yet</p>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($albumsToReview as $album)
                        <div class="group flex flex-col justify-between overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-xs dark:border-zinc-800 dark:bg-zinc-900">
                            <div class="aspect-square w-full overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                                <img
                                    src="{{ $album->cover_url }}"
                                    alt="{{ $album->title }}"
                                    class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                />
                            </div>
                            <div class="flex flex-1 flex-col justify-between p-4">
                                <div>
                                    <h4 class="font-bold text-zinc-900 line-clamp-1 dark:text-white">{{ $album->title }}</h4>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ $album->artist }}</p>
                                </div>
                                <div class="mt-3">
                                    <flux:button :href="route('albums.show', $album)" variant="ghost" size="sm" class="w-full justify-center text-xs" wire:navigate>
                                        ★ Rate & Review
                                    </flux:button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts::app>
