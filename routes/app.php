<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/home', [HomeController::class, 'home'])->name('home');

Route::get('/map', function () {
    // TODO: Implement map page
})->name('map');

Route::get('/profile', function () {
    // TODO: Implement profile page
})->name('profile');
