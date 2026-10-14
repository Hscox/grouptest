<x-layouts::app :title="$album->title . ' by ' . $album->artist">
    <div class="space-y-10 pb-16">
        <!-- Breadcrumb & Back -->
        <div class="flex items-center gap-2 text-xs font-medium text-zinc-500 dark:text-zinc-400">
            <a href="{{ route('home') }}" class="hover:underline">Home</a>
            <span>/</span>
            <a href="{{ route('albums.index') }}" class="hover:underline">Albums</a>
            <span>/</span>
            <span class="text-zinc-800 dark:text-zinc-200 truncate">{{ $album->title }}</span>
        </div>

        @if(session('status'))
            <div class="rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm font-medium text-emerald-600 dark:text-emerald-400">
                {{ session('status') }}
            </div>
        @endif

        <!-- Album Showcase Header -->
        <div class="relative overflow-hidden rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 md:p-8">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-12">
                <!-- Cover Image & Quick Streaming Links -->
                <div class="md:col-span-4 lg:col-span-4">
                    <div class="relative aspect-square w-full overflow-hidden rounded-2xl bg-zinc-100 shadow-lg dark:bg-zinc-800">
                        <img
                            src="{{ $album->cover_url }}"
                            alt="{{ $album->title }}"
                            class="h-full w-full object-cover"
                        />
                    </div>

                    <!-- Streaming Buttons -->
                    @if($album->spotify_url || $album->apple_music_url)
                        <div class="mt-4 flex flex-col gap-2">
                            @if($album->spotify_url)
                                <a
                                    href="{{ $album->spotify_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex items-center justify-center gap-2 rounded-xl bg-emerald-600/10 px-4 py-2 text-xs font-semibold text-emerald-600 transition hover:bg-emerald-600/20 dark:text-emerald-400"
                                >
                                    <span>🎧</span>
                                    <span>Listen on Spotify</span>
                                </a>
                            @endif
                            @if($album->apple_music_url)
                                <a
                                    href="{{ $album->apple_music_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="flex items-center justify-center gap-2 rounded-xl bg-pink-600/10 px-4 py-2 text-xs font-semibold text-pink-600 transition hover:bg-pink-600/20 dark:text-pink-400"
                                >
                                    <span>🎵</span>
                                    <span>Listen on Apple Music</span>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Info & Score Breakdown -->
                <div class="flex flex-col justify-between md:col-span-8 lg:col-span-8 space-y-6">
                    <div class="space-y-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <a
                                href="{{ route('albums.index', ['genre' => $album->genre->slug]) }}"
                                class="rounded-full bg-violet-100 px-3 py-1 text-xs font-semibold text-violet-700 transition hover:bg-violet-200 dark:bg-violet-900/30 dark:text-violet-300"
                            >
                                {{ $album->genre->name }}
                            </a>
                            <span class="text-xs text-zinc-400">&bull;</span>
                            <span class="text-xs font-medium text-zinc-500 dark:text-zinc-400">{{ $album->release_year }}</span>
                            @if($album->user)
                                <span class="text-xs text-zinc-400">&bull;</span>
                                <span class="text-xs text-zinc-400">Added by {{ $album->user->name }}</span>
                            @endif
                        </div>

                        <h1 class="text-3xl font-extrabold text-zinc-900 sm:text-4xl dark:text-white">
                            {{ $album->title }}
                        </h1>
                        <p class="text-xl font-medium text-zinc-600 dark:text-zinc-300">
                            {{ $album->artist }}
                        </p>

                        @if($album->description)
                            <p class="text-sm leading-relaxed text-zinc-600 dark:text-zinc-300">
                                {{ $album->description }}
                            </p>
                        @endif
                    </div>

                    <!-- Ratings Summary & Breakdown -->
                    <div class="rounded-2xl border border-zinc-100 bg-zinc-50 p-5 dark:border-zinc-800 dark:bg-zinc-800/60">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-12 sm:items-center">
                            <!-- Overall Score Card -->
                            <div class="text-center sm:col-span-5 sm:border-r sm:border-zinc-200 sm:pr-6 sm:text-left dark:border-zinc-700">
                                <div class="text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                                    Community Rating
                                </div>
                                <div class="mt-1 flex items-baseline justify-center sm:justify-start gap-2">
                                    <span class="text-4xl font-extrabold text-zinc-900 dark:text-white">
                                        {{ $album->averageRating() }}
                                    </span>
                                    <span class="text-sm font-semibold text-zinc-400">/ 5.0</span>
                                </div>
                                <div class="mt-1 flex items-center justify-center sm:justify-start gap-1 text-amber-400">
                                    @php $avg = $album->averageRating(); @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        <span>{{ $i <= round($avg) ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                                <div class="mt-1 text-xs text-zinc-500 dark:text-zinc-400">
                                    Based on {{ $album->reviewsCount() }} {{ Str::plural('review', $album->reviewsCount()) }}
                                </div>
                            </div>

                            <!-- Star Distribution Bars -->
                            <div class="sm:col-span-7 space-y-1.5">
                                @php $distribution = $album->ratingDistribution(); @endphp
                                @foreach($distribution as $stars => $data)
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="w-8 font-medium text-zinc-600 dark:text-zinc-300">{{ $stars }} ★</span>
                                        <div class="h-2 flex-1 overflow-hidden rounded-full bg-zinc-200 dark:bg-zinc-700">
                                            <div
                                                class="h-full rounded-full bg-amber-400"
                                                style="width: {{ $data['percentage'] }}%"
                                            ></div>
                                        </div>
                                        <span class="w-8 text-right text-zinc-400">{{ $data['count'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tracklist Section -->
        @if($album->tracklist && count($album->tracklist) > 0)
            <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 md:p-8">
                <div class="flex items-center justify-between border-b border-zinc-100 pb-4 dark:border-zinc-800">
                    <div>
                        <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Tracklist</h2>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ count($album->tracklist) }} songs</p>
                    </div>
                    <span class="text-lg">💿</span>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-2">
                    @foreach($album->tracklist as $index => $track)
                        <div class="flex items-center gap-3 rounded-xl p-2.5 transition hover:bg-zinc-50 dark:hover:bg-zinc-800/60">
                            <span class="flex size-6 items-center justify-center rounded-md bg-zinc-100 text-xs font-bold text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400">
                                {{ $index + 1 }}
                            </span>
                            <span class="text-sm font-medium text-zinc-800 dark:text-zinc-200">
                                {{ $track }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Livewire Reviews Section -->
        <livewire:album-reviews :album="$album" />
    </div>
</x-layouts::app>

