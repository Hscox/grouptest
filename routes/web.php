<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Livewire\AlbumCatalog;
use App\Livewire\AlbumCreate;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/albums', AlbumCatalog::class)->name('albums.index');
Route::get('/albums/create', AlbumCreate::class)->middleware('auth')->name('albums.create');
Route::get('/albums/{album}', [AlbumController::class, 'show'])->name('albums.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/settings.php';
