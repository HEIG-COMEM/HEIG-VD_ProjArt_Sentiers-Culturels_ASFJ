<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\InterestPointAdminController;
use App\Http\Controllers\MapAdminController;
use App\Http\Controllers\RouteAdminController;

/**
 * Routes for the backoffice
 * /backoffice/...
 * 
 * Protected by the admin middleware
 * 
 * @see App\Http\Middleware\AdminMiddleware
 */

Route::get('/', function () {
    return redirect()->route('backoffice');
});

Route::get('/home', function () {
    return Inertia::render('Backoffice/Index');
})->name('backoffice');

Route::resource('interest-points', InterestPointAdminController::class);
Route::resource('routes', RouteAdminController::class);

Route::get('admin-map', [MapAdminController::class, 'index'])->name('backoffice.map');
