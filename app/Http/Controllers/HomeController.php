<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Route;
use App\Http\Resources\RouteResource;

class HomeController extends Controller
{
    public function home()
    {
        $routes = Route::all();

        $routesOrderedRating = $routes->sortByDesc(function ($route) {
            return $route->rates->avg('rate');
        })->take(3);
        $routesOrderedRating->load('pictures');

        $routesOrderedRating->makeHidden('path');

        return Inertia::render('Home', [
            'routesOrderedRating' => RouteResource::collection($routesOrderedRating)
        ]);
    }
}
