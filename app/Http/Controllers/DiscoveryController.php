<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoutesNearbyRequest;
use App\Http\Resources\DifficultyResource;
use App\Http\Resources\InterestPointResource;
use App\Http\Resources\RouteResource;
use App\Http\Resources\TagResource;
use App\Models\Difficulty;
use App\Models\Tag;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\InterestPoint;

/**
 * Class DiscoveryController
 * 
 * This class is responsible for handling the logic related to discoveries.
 */
class DiscoveryController extends Controller
{
    /**
     * Display the discovery page.
     *
     * @return \Inertia\Response
     */
    public function index()
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

        $routesOrderedRating = $routes->sortByDesc(function ($route) {
            return $route->rates->avg('rate');
        })->take(3);
        $routesOrderedRating->load('pictures');

        $routesOrderedRating->makeHidden('path');

        $interestpoints = InterestPoint::all();
        $interestpoints->load('pictures');
        $interestpoints->load('tags');

        $interestpoints->map(function ($interestpoint) {
            $interestpoint->type = 'interestpoint';
            return $interestpoint;
        });

        $difficulties = Difficulty::all();
        $tags = Tag::all();

        $latestRoutes = Route::latest()->take(3)->get();
        $latestRoutes->load('pictures');

        $latestRoutes->makeHidden('path');

        return Inertia::render('Discovery', [
            'routes' => RouteResource::collection($routes),
            'interestpoints' => InterestPointResource::collection($interestpoints),
            'difficulties' => DifficultyResource::collection($difficulties),
            'tags' => TagResource::collection($tags),
            'latestRoutes' => RouteResource::collection($latestRoutes),
            'routesOrderedRating' => RouteResource::collection($routesOrderedRating)
        ]);
    }

    /**
     * Get nearby routes based on the given latitude, longitude, and radius.
     *
     * @param  \App\Http\Requests\RoutesNearbyRequest  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getNearbyRoutes(RoutesNearbyRequest $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius;

        $routes = Route::all();
        $routes->makeHidden('path');
        $routes->load('pictures');

        $routesIn = [];
        foreach ($routes as $route) {
            $distance = GeoLocateController::distance($latitude, $longitude, $route->start_lat, $route->start_long);
            if ($distance <= $radius) {
                $route->distance = $distance;
                $routesIn[] = $route;
            }
        }

        // Sort the routes by distance and take the top 3
        $routesIn = collect($routesIn)->sortBy('distance');
        $routesIn = $routesIn->take(3);

        return RouteResource::collection($routesIn);
    }
}
