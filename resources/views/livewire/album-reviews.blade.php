<div class="space-y-8">
    <!-- Status Message Alert -->
    @if($statusMessage)
        <div class="flex items-center justify-between rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-sm font-medium text-emerald-600 dark:text-emerald-400">
            <div class="flex items-center gap-2">
                <span>✓</span>
                <span>{{ $statusMessage }}</span>
            </div>
            <button
                type="button"
                wire:click="$set('statusMessage', null)"
                class="cursor-pointer text-xs text-emerald-600 hover:underline dark:text-emerald-400"
            >
                Dismiss
            </button>
        </div>
    @endif

    <!-- Review Form / User Review Box -->
    <div class="rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 md:p-8">
        @auth
            @if($currentUserReview && !$isEditing)
                <!-- User has already reviewed -->
                <div class="rounded-2xl border border-violet-200 bg-violet-50/50 p-6 dark:border-violet-900/50 dark:bg-violet-950/20">
                    <div class="flex items-start justify-between gap-4">
                        <div class="space-y-1">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-violet-600 px-2.5 py-0.5 text-xs font-semibold text-white">
                                Your Review
                            </span>
                            <div class="flex items-center gap-1 text-amber-400 pt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= $currentUserReview->rating ? '★' : '☆' }}</span>
                                @endfor
                                <span class="ml-1 text-xs font-bold text-zinc-700 dark:text-zinc-200">
                                    {{ $currentUserReview->rating }}.0
                                </span>
                            </div>
                        </div>

                        <!-- Edit / Delete actions -->
                        <div class="flex items-center gap-2">
                            <flux:button
                                wire:click="editReview({{ $currentUserReview->id }})"
                                size="sm"
                                variant="ghost"
                                class="text-xs"
                            >
                                Edit Review
                            </flux:button>
                            <flux:button
                                wire:click="deleteReview({{ $currentUserReview->id }})"
                                wire:confirm="Are you sure you want to delete your review?"
                                size="sm"
                                variant="danger"
                                class="text-xs"
                            >
                                Delete
                            </flux:button>
                        </div>
                    </div>

                    <h3 class="mt-3 text-lg font-bold text-zinc-900 dark:text-white">
                        {{ $currentUserReview->title }}
                    </h3>

                    <p class="mt-2 text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">
                        {{ $currentUserReview->content }}
                    </p>

                    @if($currentUserReview->favorite_track)
                        <div class="mt-3 inline-flex items-center gap-1.5 rounded-md bg-white px-2.5 py-1 text-xs font-medium text-zinc-800 shadow-xs dark:bg-zinc-800 dark:text-zinc-200">
                            <span>🎵 Favorite Track:</span>
                            <span class="font-bold">{{ $currentUserReview->favorite_track }}</span>
                        </div>
                    @endif
                </div>
            @else
                <!-- Review Form (Creating or Editing) -->
                <form wire:submit="saveReview" class="space-y-5">
                    <div class="border-b border-zinc-100 pb-4 dark:border-zinc-800">
                        <h2 class="text-xl font-bold text-zinc-900 dark:text-white">
                            {{ $isEditing ? 'Edit Your Review' : 'Write a Review' }}
                        </h2>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">
                            Share your honest thoughts, track favorites, and overall score for this album.
                        </p>
                    </div>

                    <!-- Star Rating Selector -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-300 mb-2">
                            Your Rating
                        </label>
                        <div class="flex items-center gap-2">
                            @for($i = 1; $i <= 5; $i++)
                                <button
                                    type="button"
                                    wire:click="setRating({{ $i }})"
                                    class="cursor-pointer text-3xl transition transform hover:scale-110 focus:outline-hidden {{ $i <= $rating ? 'text-amber-400' : 'text-zinc-300 dark:text-zinc-600' }}"
                                >
                                    ★
                                </button>
                            @endfor
                            <span class="ml-2 rounded-lg bg-zinc-100 px-2.5 py-1 text-sm font-bold text-zinc-800 dark:bg-zinc-800 dark:text-zinc-200">
                                {{ $rating }} of 5 Stars
                            </span>
                        </div>
                        @error('rating') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Review Headline/Title -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-300 mb-1">
                            Review Title
                        </label>
                        <input
                            type="text"
                            wire:model="title"
                            placeholder="e.g., A timeless psychedelic masterwork"
                            class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-violet-500 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-violet-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                        />
                        @error('title') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Review Body -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-300 mb-1">
                            Your Written Critique
                        </label>
                        <textarea
                            wire:model="content"
                            rows="4"
                            placeholder="What did you love or dislike about the production, vocals, lyrics, or sequencing?"
                            class="w-full rounded-xl border border-zinc-200 bg-zinc-50 p-4 text-sm text-zinc-900 placeholder-zinc-400 focus:border-violet-500 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-violet-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                        ></textarea>
                        @error('content') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Favorite Track & Recommend checkbox -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-600 dark:text-zinc-300 mb-1">
                                Favorite Track (Optional)
                            </label>
                            <input
                                type="text"
                                wire:model="favorite_track"
                                placeholder="e.g. Track name"
                                class="w-full rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-2.5 text-sm text-zinc-900 placeholder-zinc-400 focus:border-violet-500 focus:bg-white focus:outline-hidden focus:ring-2 focus:ring-violet-500 dark:border-zinc-700 dark:bg-zinc-800 dark:text-white"
                            />
                            @error('favorite_track') <span class="text-xs text-rose-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center pt-6">
                            <label class="relative flex cursor-pointer items-center gap-3">
                                <input
                                    type="checkbox"
                                    wire:model="is_recommended"
                                    class="size-4 rounded border-zinc-300 text-violet-600 focus:ring-violet-500 dark:border-zinc-700 dark:bg-zinc-800"
                                />
                                <span class="text-sm font-medium text-zinc-800 dark:text-zinc-200">
                                    I recommend this album to others
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Form Action Buttons -->
                    <div class="flex items-center gap-3 pt-2">
                        <button
                            type="submit"
                            class="cursor-pointer inline-flex items-center justify-center rounded-xl bg-violet-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-violet-500"
                        >
                            {{ $isEditing ? 'Update Review' : 'Publish Review' }}
                        </button>

                        @if($isEditing)
                            <button
                                type="button"
                                wire:click="cancelEditing"
                                class="cursor-pointer rounded-xl px-4 py-2.5 text-sm font-medium text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white"
                            >
                                Cancel
                            </button>
                        @endif
                    </div>
                </form>
            @endif
        @else
            <!-- Guest Prompt -->
            <div class="flex flex-col items-center justify-between gap-4 text-center sm:flex-row sm:text-left">
                <div>
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Have thoughts on this album?</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Join the discussion by rating this record and posting your review.</p>
                </div>
                <div class="flex items-center gap-3">
                    <flux:button :href="route('login')" variant="ghost" wire:navigate>
                        Log in
                    </flux:button>
                    <flux:button :href="route('register')" variant="primary" wire:navigate>
                        Sign up to Review
                    </flux:button>
                </div>
            </div>
        @endauth
    </div>

    <!-- Community Reviews List -->
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">
                Community Reviews ({{ count($reviews) }})
            </h2>
        </div>

        @if(count($reviews) > 0)
            <div class="space-y-4">
                @foreach($reviews as $review)
                    <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-xs transition hover:border-zinc-300 dark:border-zinc-800 dark:bg-zinc-900 dark:hover:border-zinc-700">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <!-- User Avatar & Metadata -->
                            <div class="flex items-center gap-3">
                                <flux:avatar :name="$review->user->name" size="md" />
                                <div>
                                    <div class="font-bold text-zinc-900 dark:text-white">{{ $review->user->name }}</div>
                                    <div class="text-xs text-zinc-400">{{ $review->created_at->diffForHumans() }}</div>
                                </div>
                            </div>

                            <!-- Rating & Recommendation -->
                            <div class="flex items-center gap-2">
                                <div class="flex items-center gap-1 text-amber-400 text-sm">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span>{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                    @endfor
                                    <span class="ml-1 text-xs font-bold text-zinc-700 dark:text-zinc-200">
                                        {{ $review->rating }}.0
                                    </span>
                                </div>

                                @if($review->is_recommended)
                                    <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-medium text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400">
                                        Recommended
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Review Title & Content -->
                        <div class="mt-4 space-y-2">
                            <h3 class="text-base font-bold text-zinc-900 dark:text-white">
                                {{ $review->title }}
                            </h3>
                            <p class="text-sm leading-relaxed text-zinc-700 dark:text-zinc-300 whitespace-pre-line">
                                {{ $review->content }}
                            </p>
                        </div>

                        <!-- Highlight Track -->
                        @if($review->favorite_track)
                            <div class="mt-3 inline-flex items-center gap-1.5 rounded-md bg-zinc-100 px-2.5 py-1 text-xs font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300">
                                <span>🎵 Highlight Track:</span>
                                <span class="font-semibold">{{ $review->favorite_track }}</span>
                            </div>
                        @endif

                        <!-- Footnote: Likes / Helpful and Ownership Actions -->
                        <div class="mt-4 flex items-center justify-between border-t border-zinc-100 pt-3 text-xs text-zinc-500 dark:border-zinc-800 dark:text-zinc-400">
                            <!-- Like button -->
                            <button
                                type="button"
                                wire:click="toggleLike({{ $review->id }})"
                                class="cursor-pointer inline-flex items-center gap-1.5 rounded-lg px-2.5 py-1 transition {{ auth()->check() && $review->isLikedBy(auth()->user()) ? 'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-300' : 'hover:bg-zinc-100 text-zinc-600 dark:text-zinc-400 dark:hover:bg-zinc-800' }}"
                            >
                                <span>👍</span>
                                <span>{{ $review->likes_count }} {{ Str::plural('Helpful', $review->likes_count) }}</span>
                            </button>

                            <!-- Author Actions -->
                            @if(auth()->check() && auth()->id() === $review->user_id)
                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        wire:click="editReview({{ $review->id }})"
                                        class="cursor-pointer text-zinc-500 hover:text-zinc-900 hover:underline dark:hover:text-white"
                                    >
                                        Edit
                                    </button>
                                    <span>&bull;</span>
                                    <button
                                        type="button"
                                        wire:click="deleteReview({{ $review->id }})"
                                        wire:confirm="Are you sure you want to delete your review?"
                                        class="cursor-pointer text-rose-500 hover:text-rose-600 hover:underline"
                                    >
                                        Delete
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-dashed border-zinc-300 p-8 text-center dark:border-zinc-700">
                <p class="text-sm text-zinc-500 dark:text-zinc-400">No reviews yet for this album. Be the first to review it!</p>
            </div>
        @endif
    </div>
</div>
