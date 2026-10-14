<?php

use App\Livewire\AlbumCatalog;
use App\Livewire\AlbumCreate;
use App\Livewire\AlbumReviews;
use App\Models\Album;
use App\Models\Genre;
use App\Models\Review;
use App\Models\User;
use Livewire\Livewire;

test('landing page renders successfully and displays music content', function () {
    $genre = Genre::factory()->create(['name' => 'Rock', 'slug' => 'rock']);
    $album = Album::factory()->create([
        'genre_id' => $genre->id,
        'title' => 'Dark Side of the Moon',
        'artist' => 'Pink Floyd',
    ]);
    Review::factory()->create([
        'album_id' => $album->id,
        'title' => 'Incredible sonic masterpiece',
    ]);

    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('Dark Side of the Moon')
        ->assertSee('Pink Floyd')
        ->assertSee('Incredible sonic masterpiece');
});

test('album catalog renders and supports searching and filtering', function () {
    $rock = Genre::factory()->create(['name' => 'Rock', 'slug' => 'rock-test']);
    $jazz = Genre::factory()->create(['name' => 'Jazz', 'slug' => 'jazz-test']);

    $rockAlbum = Album::factory()->create([
        'genre_id' => $rock->id,
        'title' => 'Led Zeppelin IV',
        'artist' => 'Led Zeppelin',
    ]);
    $jazzAlbum = Album::factory()->create([
        'genre_id' => $jazz->id,
        'title' => 'A Love Supreme',
        'artist' => 'John Coltrane',
    ]);

    Livewire::test(AlbumCatalog::class)
        ->assertSee('Led Zeppelin IV')
        ->assertSee('A Love Supreme')
        ->set('search', 'Zeppelin')
        ->assertSee('Led Zeppelin IV')
        ->assertDontSee('A Love Supreme')
        ->set('search', '')
        ->set('genre', 'jazz-test')
        ->assertSee('A Love Supreme')
        ->assertDontSee('Led Zeppelin IV');
});

test('album detail page renders and shows tracklist', function () {
    $genre = Genre::factory()->create();
    $album = Album::factory()->create([
        'genre_id' => $genre->id,
        'title' => 'OK Computer',
        'artist' => 'Radiohead',
        'tracklist' => ['Airbag', 'Paranoid Android', 'Subterranean Homesick Alien'],
    ]);

    $response = $this->get(route('albums.show', $album));

    $response->assertOk()
        ->assertSee('OK Computer')
        ->assertSee('Radiohead')
        ->assertSee('Paranoid Android');
});

test('authenticated user can publish a review on an album', function () {
    $user = User::factory()->create();
    $album = Album::factory()->create();

    $this->actingAs($user);

    Livewire::test(AlbumReviews::class, ['album' => $album])
        ->set('rating', 5)
        ->set('title', 'Absolute Classic')
        ->set('content', 'Every single track on this album is thoroughly breathtaking and memorable.')
        ->set('favorite_track', 'Track 2')
        ->set('is_recommended', true)
        ->call('saveReview')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('reviews', [
        'user_id' => $user->id,
        'album_id' => $album->id,
        'rating' => 5,
        'title' => 'Absolute Classic',
        'favorite_track' => 'Track 2',
    ]);
});

test('authenticated user can edit their existing review', function () {
    $user = User::factory()->create();
    $album = Album::factory()->create();

    $review = Review::factory()->create([
        'user_id' => $user->id,
        'album_id' => $album->id,
        'rating' => 3,
        'title' => 'Pretty good',
        'content' => 'Decent effort with some cool guitar riffs.',
    ]);

    $this->actingAs($user);

    Livewire::test(AlbumReviews::class, ['album' => $album])
        ->call('editReview', $review->id)
        ->set('rating', 5)
        ->set('title', 'Changed my mind - this is pure gold')
        ->call('saveReview')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('reviews', [
        'id' => $review->id,
        'rating' => 5,
        'title' => 'Changed my mind - this is pure gold',
    ]);
});

test('authenticated user can delete their review', function () {
    $user = User::factory()->create();
    $album = Album::factory()->create();

    $review = Review::factory()->create([
        'user_id' => $user->id,
        'album_id' => $album->id,
    ]);

    $this->actingAs($user);

    Livewire::test(AlbumReviews::class, ['album' => $album])
        ->call('deleteReview', $review->id);

    $this->assertDatabaseMissing('reviews', [
        'id' => $review->id,
    ]);
});

test('user cannot delete another user review', function () {
    $author = User::factory()->create();
    $anotherUser = User::factory()->create();
    $album = Album::factory()->create();

    $review = Review::factory()->create([
        'user_id' => $author->id,
        'album_id' => $album->id,
    ]);

    $this->actingAs($anotherUser);

    Livewire::test(AlbumReviews::class, ['album' => $album])
        ->call('deleteReview', $review->id);

    $this->assertDatabaseHas('reviews', [
        'id' => $review->id,
    ]);
});

test('guest is redirected to login when trying to post a review', function () {
    $album = Album::factory()->create();

    Livewire::test(AlbumReviews::class, ['album' => $album])
        ->set('rating', 5)
        ->set('title', 'Guest review attempt')
        ->set('content', 'This should not be saved because I am a guest.')
        ->call('saveReview')
        ->assertRedirect(route('login'));

    $this->assertDatabaseMissing('reviews', [
        'title' => 'Guest review attempt',
    ]);
});

test('authenticated user can toggle helpful like on a review', function () {
    $author = User::factory()->create();
    $fan = User::factory()->create();
    $album = Album::factory()->create();

    $review = Review::factory()->create([
        'user_id' => $author->id,
        'album_id' => $album->id,
        'likes_count' => 0,
    ]);

    $this->actingAs($fan);

    // Toggle on
    Livewire::test(AlbumReviews::class, ['album' => $album])
        ->call('toggleLike', $review->id);

    $this->assertDatabaseHas('review_likes', [
        'user_id' => $fan->id,
        'review_id' => $review->id,
    ]);
    expect($review->fresh()->likes_count)->toBe(1);

    // Toggle off
    Livewire::test(AlbumReviews::class, ['album' => $album])
        ->call('toggleLike', $review->id);

    $this->assertDatabaseMissing('review_likes', [
        'user_id' => $fan->id,
        'review_id' => $review->id,
    ]);
    expect($review->fresh()->likes_count)->toBe(0);
});

test('authenticated user can submit a new album', function () {
    $user = User::factory()->create();
    $genre = Genre::factory()->create();

    $this->actingAs($user);

    Livewire::test(AlbumCreate::class)
        ->set('title', 'Kid A')
        ->set('artist', 'Radiohead')
        ->set('release_year', 2000)
        ->set('genre_id', $genre->id)
        ->set('description', 'An experimental electronic rock masterpiece.')
        ->set('tracklist_input', "Everything In Its Right Place\nKid A\nThe National Anthem")
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect();

    $this->assertDatabaseHas('albums', [
        'title' => 'Kid A',
        'artist' => 'Radiohead',
        'release_year' => 2000,
        'genre_id' => $genre->id,
    ]);
});

test('dashboard displays user stats and reviews', function () {
    $user = User::factory()->create();
    $album = Album::factory()->create(['title' => 'Pet Sounds']);
    Review::factory()->create([
        'user_id' => $user->id,
        'album_id' => $album->id,
        'title' => 'Masterful baroque pop harmonies',
    ]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));

    $response->assertOk()
        ->assertSee('Pet Sounds')
        ->assertSee('Masterful baroque pop harmonies')
        ->assertSee('Reviews Written');
});
