<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Homepage
Route::get('/', [PageController::class, 'home'])->name('home');

// Dynamic pages
Route::get('/{slug}', [PageController::class, 'show'])->name('page.show')
    ->where('slug', '[a-z0-9-_]+');

// GrapesJS save route (for authenticated users only)
Route::post('/{slug}/save-grapesjs', [PageController::class, 'saveGrapesJs'])
    ->name('page.save-grapesjs')
    ->where('slug', '[a-z0-9-_]+')
    ->middleware('auth');
