<?php

use App\Http\Controllers\SearchController;
use App\Http\Controllers\HealthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Search page
Route::get('/search', function () {
    return view('search');
});

// Index page
Route::get('/index', function () {
    return view('index');
});

// Delete page
Route::get('/delete', function () {
    return view('delete');
});

// Search comparison endpoint (returns view)
Route::get('/compare-search', [SearchController::class, 'compareSearch']);

// Index blogs
Route::post('/index-blogs', [App\Http\Controllers\IndexController::class, 'create']);

// Delete index
Route::post('/delete-index', [App\Http\Controllers\IndexController::class, 'delete']);

// Health check endpoints
Route::get('/health/elasticsearch', [HealthController::class, 'elasticsearch']);
Route::get('/health/meilisearch', [HealthController::class, 'meilisearch']);
