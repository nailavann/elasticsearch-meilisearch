<?php

use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

// Index management endpoints
Route::delete('/index', [SearchController::class, 'deleteIndex']);
Route::post('/index/blogs', [SearchController::class, 'indexBlogs']);
