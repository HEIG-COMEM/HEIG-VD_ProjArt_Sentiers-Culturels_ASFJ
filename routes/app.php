<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/home', [HomeController::class, 'home'])->name('home');

Route::get('/discovery', function () {
    // TODO: Implement discovery page
})->name('discovery');

Route::get('/map', function () {
    // TODO: Implement map page
})->name('map');

Route::get('/favourite', function () {
    // TODO: Implement favourite page
})->name('favourite');

Route::get('/collection', function () {
    return Inertia::render('Collection');
})->name('collection');
