<?php

use App\Http\Controllers\CollectionAdminController;
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

Route::resource('interest-points', InterestPointAdminController::class)->names([
    'index' => 'backoffice.interest-points',
    'create' => 'backoffice.interest-points.create',
    'store' => 'backoffice.interest-points.store',
    'show' => 'backoffice.interest-points.show',
    'edit' => 'backoffice.interest-points.edit',
    'update' => 'backoffice.interest-points.update',
    'destroy' => 'backoffice.interest-points.destroy',
]);

Route::resource('routes', RouteAdminController::class)->names([
    'index' => 'backoffice.routes',
    'create' => 'backoffice.routes.create',
    'store' => 'backoffice.routes.store',
    'show' => 'backoffice.routes.show',
    'edit' => 'backoffice.routes.edit',
    'update' => 'backoffice.routes.update',
    'destroy' => 'backoffice.routes.destroy',
]);

Route::get('admin-collection', [CollectionAdminController::class, 'index'])->name('backoffice.collection');
Route::get('admin-map', [MapAdminController::class, 'index'])->name('backoffice.map');
