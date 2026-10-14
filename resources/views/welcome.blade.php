// hihshhshshs

<x-layouts::app :title="__('TuneEcho - Music & Album Reviews')">
    <div class="space-y-12 pb-16">
        <!-- Hero Section -->
        <section class="relative overflow-hidden rounded-3xl bg-linear-to-br from-zinc-900 via-zinc-950 to-neutral-900 px-6 py-16 text-white shadow-xl sm:px-12 sm:py-20 border border-zinc-800">
            <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-violet-600/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 h-96 w-96 rounded-full bg-pink-600/20 blur-3xl pointer-events-none"></div>

            <div class="relative max-w-3xl space-y-6">
                <div class="inline-flex items-center gap-2 rounded-full border border-violet-500/30 bg-violet-500/10 px-4 py-1.5 text-xs font-medium text-violet-300">
                    <span class="flex h-2 w-2 rounded-full bg-violet-400 animate-pulse"></span>
                    <span>The modern community for album critique</span>
                </div>

                <h1 class="text-4xl font-extrabold tracking-tight sm:text-6xl text-white">
                    Every album tells a story. <span class="bg-linear-to-r from-violet-400 to-pink-400 bg-clip-text text-transparent">Rate & review</span> yours.
                </h1>

                <p class="text-lg text-zinc-300 sm:text-xl">
                    Discover legendary classics, share in-depth reviews, track your favorite songs, and connect with listeners from all genres.
                </p>

                <!-- Search form shortcut -->
                <form action="{{ route('albums.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 pt-2">
                    <div class="relative flex-1">
                        <flux:icon name="magnifying-glass" class="absolute left-4 top-1/2 -translate-y-1/2 size-5 text-zinc-400" />
                        <input
                            type="text"
                            name="search"
                            placeholder="Search by album title, artist, or vibe..."
                            class="w-full rounded-xl border border-zinc-700 bg-zinc-800/80 pl-11 pr-4 py-3 text-sm text-white placeholder-zinc-400 backdrop-blur-sm focus:border-violet-500 focus:outline-hidden focus:ring-2 focus:ring-violet-500"
                        />
                    </div>
                    <button
                        type="submit"
                        class="cursor-pointer inline-flex items-center justify-center rounded-xl bg-violet-600 px-6 py-3 font-semibold text-white shadow-lg transition hover:bg-violet-500"
                    >
                        Search Music
                    </button>
                </form>

                <!-- Genre Quick Filters -->
                <div class="flex flex-wrap items-center gap-2 pt-2 text-xs">
                    <span class="text-zinc-400">Popular Genres:</span>
                    @foreach($genres->take(5) as $genre)
                        <a
                            href="{{ route('albums.index', ['genre' => $genre->slug]) }}"
                            class="rounded-full border border-zinc-700 bg-zinc-800/60 px-3 py-1 font-medium text-zinc-200 transition hover:border-violet-400 hover:text-white"
                        >
                            {{ $genre->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="mt-12 grid grid-cols-2 gap-4 border-t border-zinc-800 pt-8 sm:grid-cols-4">
                <div>
                    <div class="text-2xl font-bold text-white sm:text-3xl">{{ $stats['total_albums'] }}</div>
                    <div class="text-xs text-zinc-400">Curated Albums</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-violet-400 sm:text-3xl">{{ $stats['total_reviews'] }}</div>
                    <div class="text-xs text-zinc-400">Critic Reviews</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-pink-400 sm:text-3xl">{{ $stats['total_critics'] }}</div>
                    <div class="text-xs text-zinc-400">Music Reviewers</div>
                </div>
                <div>
                    <div class="text-2xl font-bold text-amber-400 sm:text-3xl">★ {{ $stats['average_rating'] }}</div>
                    <div class="text-xs text-zinc-400">Avg Community Rating</div>
                </div>
            </div>
        </section>

        <!-- Top Rated Albums Section -->
        <section class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Top Rated Albums</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Acclaimed masterpieces rated by the community</p>
                </div>
                <flux:button :href="route('albums.index', ['sort' => 'highest_rated'])" variant="ghost" class="text-sm">
                    View All →
                </flux:button>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($topAlbums as $album)
                    <div class="group relative flex flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-xs transition hover:shadow-lg dark:border-zinc-800 dark:bg-zinc-900">
                        <div class="relative aspect-square w-full overflow-hidden bg-zinc-100 dark:bg-zinc-800">
                            <img
                                src="{{ $album->cover_url }}"
                                alt="{{ $album->title }}"
                                class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                                loading="lazy"
                            />
                            <div class="absolute top-3 right-3 flex items-center gap-1 rounded-full bg-zinc-900/80 px-2.5 py-1 text-xs font-semibold text-amber-400 backdrop-blur-md">
                                <span>★</span>
                                <span>{{ $album->averageRating() }}</span>
                            </div>
                            <div class="absolute bottom-3 left-3">
                                <span class="rounded-md bg-zinc-900/80 px-2.5 py-1 text-xs font-medium text-zinc-200 backdrop-blur-md">
                                    {{ $album->genre->name }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-1 flex-col justify-between p-5">
                            <div>
                                <h3 class="text-lg font-bold text-zinc-900 line-clamp-1 dark:text-white">
                                    <a href="{{ route('albums.show', $album) }}" class="hover:underline">
                                        {{ $album->title }}
                                    </a>
                                </h3>
                                <p class="text-sm font-medium text-zinc-500 dark:text-zinc-400">{{ $album->artist }} &bull; {{ $album->release_year }}</p>
                                
                                <p class="mt-2 text-xs text-zinc-600 line-clamp-2 dark:text-zinc-400">
                                    {{ $album->description }}
                                </p>
                            </div>

                            <div class="mt-4 flex items-center justify-between border-t border-zinc-100 pt-3 text-xs text-zinc-500 dark:border-zinc-800 dark:text-zinc-400">
                                <span>{{ $album->reviews_count }} {{ Str::plural('review', $album->reviews_count) }}</span>
                                <a href="{{ route('albums.show', $album) }}" class="font-semibold text-violet-600 hover:text-violet-500 dark:text-violet-400">
                                    View Album &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Recent Reviews Feed -->
        <section class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Recent Reviews</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">What music listeners are saying right now</p>
                </div>
                <flux:button :href="route('albums.index')" variant="ghost" class="text-sm">
                    Explore Music →
                </flux:button>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($recentReviews as $review)
                    <div class="flex flex-col justify-between rounded-2xl border border-zinc-200 bg-white p-5 shadow-xs transition hover:border-zinc-300 dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-zinc-700">
                        <div class="space-y-3">
                            <!-- Album Info Snippet -->
                            <div class="flex items-center gap-3 border-b border-zinc-100 pb-3 dark:border-zinc-800">
                                <img
                                    src="{{ $review->album->cover_url }}"
                                    alt="{{ $review->album->title }}"
                                    class="size-12 rounded-lg object-cover shadow-xs"
                                    loading="lazy"
                                />
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('albums.show', $review->album) }}" class="truncate block text-sm font-bold text-zinc-900 hover:underline dark:text-white">
                                        {{ $review->album->title }}
                                    </a>
                                    <div class="truncate text-xs text-zinc-500 dark:text-zinc-400">{{ $review->album->artist }}</div>
                                </div>
                            </div>

                            <!-- Rating & Title -->
                            <div>
                                <div class="flex items-center gap-1 text-amber-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                    @endfor
                                    <span class="ml-1 text-xs font-semibold text-zinc-600 dark:text-zinc-300">
                                        {{ $review->rating }}.0
                                    </span>
                                </div>
                                <h4 class="mt-1 font-semibold text-zinc-900 dark:text-white line-clamp-1">
                                    {{ $review->title }}
                                </h4>
                            </div>

                            <!-- Review Body -->
                            <p class="text-xs text-zinc-600 line-clamp-3 leading-relaxed dark:text-zinc-300">
                                "{{ $review->content }}"
                            </p>

                            <!-- Highlight Track -->
                            @if($review->favorite_track)
                                <div class="inline-flex items-center gap-1.5 rounded-md bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                    <span>🎵</span>
                                    <span class="truncate">Highlight: {{ $review->favorite_track }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Reviewer Footnote -->
                        <div class="mt-4 flex items-center justify-between border-t border-zinc-100 pt-3 text-xs text-zinc-500 dark:border-zinc-800 dark:text-zinc-400">
                            <div class="flex items-center gap-2">
                                <flux:avatar :name="$review->user->name" size="xs" />
                                <span class="font-medium text-zinc-800 dark:text-zinc-200">{{ $review->user->name }}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span>👍</span>
                                <span>{{ $review->likes_count }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Browse By Genre -->
        <section class="space-y-6">
            <div>
                <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">Explore by Genre</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400">Find records across your favorite sonic styles</p>
            </div>

            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach($genres as $genre)
                    <a
                        href="{{ route('albums.index', ['genre' => $genre->slug]) }}"
                        class="group flex flex-col justify-between rounded-xl border border-zinc-200 bg-white p-4 transition hover:border-violet-500 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-violet-500"
                    >
                        <div>
                            <div class="font-bold text-zinc-900 group-hover:text-violet-600 dark:text-white dark:group-hover:text-violet-400">
                                {{ $genre->name }}
                            </div>
                            <p class="mt-1 text-xs text-zinc-500 line-clamp-2 dark:text-zinc-400">
                                {{ $genre->description }}
                            </p>
                        </div>
                        <div class="mt-3 text-xs font-semibold text-zinc-400 group-hover:text-violet-500">
                            {{ $genre->albums_count }} {{ Str::plural('album', $genre->albums_count) }} &rarr;
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- CTA Banner -->
        <section class="rounded-3xl border border-zinc-200 bg-linear-to-r from-violet-600 to-indigo-700 p-8 text-center text-white sm:p-12 shadow-lg dark:border-zinc-800">
            <h2 class="text-3xl font-extrabold sm:text-4xl">Have an album on repeat?</h2>
            <p class="mx-auto mt-3 max-w-xl text-violet-100">
                Join our community of passionate audiophiles. Drop your score, highlight your standout tracks, and share your perspective.
            </p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                @auth
                    <flux:button :href="route('albums.create')" variant="primary" class="bg-white text-violet-900 hover:bg-zinc-100 font-semibold shadow-md">
                        + Submit New Album
                    </flux:button>
                    <flux:button :href="route('albums.index')" variant="ghost" class="text-white hover:bg-white/10">
                        Browse Catalog
                    </flux:button>
                @else
                    <flux:button :href="route('register')" variant="primary" class="bg-white text-violet-900 hover:bg-zinc-100 font-semibold shadow-md">
                        Create Free Account
                    </flux:button>
                    <flux:button :href="route('login')" variant="ghost" class="text-white hover:bg-white/10">
                        Sign In
                    </flux:button>
                @endauth
            </div>
        </section>
    </div>
</x-layouts::app>
