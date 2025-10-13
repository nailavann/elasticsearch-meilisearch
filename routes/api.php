<?php

use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

// Search comparison endpoints
Route::get('/search/compare', [SearchController::class, 'compareSearch']);

// Index management endpoints
Route::delete('/index', [SearchController::class, 'deleteIndex']);
Route::post('/index/blogs', [SearchController::class, 'indexBlogs']);

