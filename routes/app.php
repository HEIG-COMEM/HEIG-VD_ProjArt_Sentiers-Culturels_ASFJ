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

Route::get('/profile', function () {
    return Inertia::render('Profile/Index');
})->name('profile');

Route::get('/route/{uuid}', [RouteController::class, 'show'])->name('route.show');

/* ---------- Profile Section Route ---------- */
Route::group(['prefix' => 'profile'], function () {
    Route::get('/', function () {
        return Inertia::render('Profile/Index');
    })->name('profile');

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

    Route::get('/history', function () {
        return Inertia::render('Profile/History');
    })->name('profile.history');

    /* ---------- Collection Section Route ---------- */
    Route::group(['prefix' => 'collection'], function () {
        Route::get('/', function () {
            return Inertia::render('Profile/Collection/Index');
        })->name('profile.collection');

        /* ---------- Region Section Route ---------- */
        Route::group(['prefix' => 'region'], function () {
            Route::get('/', function () {
                return Inertia::render('Profile/Collection/Region/Index');
            })->name('profile.collection.region');

            Route::get('/lavaux', function () {
                return Inertia::render('Profile/Collection/Region/Lavaux');
            })->name('profile.collection.region.lavaux');
        });

        /* ---------- City Section Route ---------- */
        Route::group(['prefix' => 'city'], function () {
            Route::get('/', function () {
                return Inertia::render('Profile/Collection/City/Index');
            })->name('profile.collection.city');
        });

        Route::get('/architecture', function () {
            return Inertia::render('Profile/Collection/Architecture');
        })->name('profile.collection.architecture');

        Route::get('/castle', function () {
            return Inertia::render('Profile/Collection/Castle');
        })->name('profile.collection.castle');
    });
});

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
