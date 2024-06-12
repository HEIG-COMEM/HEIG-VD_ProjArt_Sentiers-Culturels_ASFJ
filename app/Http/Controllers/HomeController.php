<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Route;
use App\Models\InterestPoint;
use App\Http\Resources\RouteResource;
use App\Http\Resources\InterestPointResource;

/**
 * Class HomeController
 *
 * This class is responsible for handling requests related to the home page.
 */
class HomeController extends Controller
{
    /**
     * Display the home page.
     *
     * @return \Inertia\Response
     */
    public function home(): \Inertia\Response
    {
        $routes = Route::all();
        $routes->load('difficulty');
        $routes->load('pictures');
        $routes->load('tags');
        $routes->load('rates');

        $routes->map(function ($route) {
            $route->type = 'route';
            return $route;
        });

        $routes->makeHidden('path');

        $interestpoints = InterestPoint::all();
        $interestpoints->load('pictures');

        $interestpoints->map(function ($interestpoint) {
            $interestpoint->type = 'interestpoint';
            return $interestpoint;
        });

        $routesOrderedRating = $routes->sortByDesc(function ($route) {
            return $route->rates->avg('rate');
        })->take(3);
        $routesOrderedRating->load('pictures');

        $routesOrderedRating->makeHidden('path');

        return Inertia::render('Home', [
            'routes' => RouteResource::collection($routes),
            'interestpoints' => InterestPointResource::collection($interestpoints),
            'routesOrderedRating' => RouteResource::collection($routesOrderedRating)
        ]);
    }
}
