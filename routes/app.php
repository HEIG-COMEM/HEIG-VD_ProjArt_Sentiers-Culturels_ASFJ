<?php

use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\InterestPointController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/home', [HomeController::class, 'home'])->name('home');

Route::get('/discovery', function () {
    // TODO: Implement discovery page
})->name('discovery');

Route::get('/map', [MapController::class, 'index'])->name('map');

Route::get('/favorite', [FavoriteController::class, 'index'])->name('favorite');

Route::get('/collection', function () {
    return Inertia::render('Collection');
})->name('collection');

Route::get('/route/{uuid}', [RouteController::class, 'show'])->name('route.show');

Route::get('/api/interest-point/{uuid}', [InterestPointController::class, 'show']);
Route::get('/interest-point/{uuid}', function ($uuid) {
    return Inertia::render('InterestPoint/Show', [
        'uuid' => $uuid
    ]);
});

// BACKOFFICE
Route::group(['prefix' => 'backoffice', 'middleware' => 'admin'], function () {
    require __DIR__ . '/backoffice.php';
});
