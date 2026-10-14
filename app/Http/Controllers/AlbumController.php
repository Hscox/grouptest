<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Contracts\View\View;

class AlbumController extends Controller
{
    /**
     * Display the specified album and its reviews.
     */
    public function show(Album $album): View
    {
        $album->load([
            'genre',
            'user',
            'reviews' => function ($query) {
                $query->with(['user', 'likes'])->latest();
            },
        ]);

        return view('albums.show', compact('album'));
    }
}
