<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\DiscoveryController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\InterestPointController;
use App\Http\Controllers\ProfileCollectionController;
use App\Http\Controllers\ProfilePageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/home', [HomeController::class, 'home'])->name('home');

Route::get('/discovery', [DiscoveryController::class, 'index'])->name('discovery');

Route::get('/map', [MapController::class, 'index'])->name('map');

Route::get('/favorite', [FavoriteController::class, 'index'])->name('favorite');

Route::get('/profile', function () {
    return Inertia::render('Profile/Index');
})->name('profile');

Route::get('/route/{uuid}', [RouteController::class, 'show'])->name('route.show');

/* ---------- Profile Section Route ---------- */
Route::group(['prefix' => 'profile', 'middleware' => 'auth'], function () {
    Route::get('/', [ProfilePageController::class, 'index'])->name('profile');

    Route::get('/account', function () {
        return Inertia::render('Profile/Account');
    })->name('profile.account');

    /* ---------- Settings Section Route ---------- */
    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', function () {
            return Inertia::render('Profile/Settings/Index');
        })->name('profile.settings');

        Route::get('/tutorial', function () {
            return Inertia::render('Profile/Settings/Tutorial');
        })->name('settings.tutorial');
    });

    Route::get('/history', [ProfilePageController::class, 'history'])->name('profile.history');

    /* ---------- Collection Section Route ---------- */
    Route::group(['prefix' => 'collection'], function () {
        Route::get('/', [ProfileCollectionController::class, 'index'])->name('profile.collection');
        Route::get('/{uuid}', [ProfileCollectionController::class, 'show'])->name('profile.collection.show');
    });
});

/* ---------- API Section Route ---------- */
Route::group(['prefix' => 'api'], function () {
    Route::get('/interest-point/{uuid}', [InterestPointController::class, 'show']);
    Route::get('/routes/nearby', [DiscoveryController::class, 'getNearbyRoutes']);
});


Route::get('/interest-point/{uuid}', function ($uuid) {
    return Inertia::render('InterestPoint/Show', [
        'uuid' => $uuid
    ]);
});

// BACKOFFICE
Route::group(['prefix' => 'backoffice', 'middleware' => 'admin'], function () {
    require __DIR__ . '/backoffice.php';
});
