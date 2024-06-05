<?php

namespace App\Http\Controllers;

use App\Http\Resources\RouteResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\RouteHistory;
use Illuminate\Support\Facades\Auth;

class RouteController extends Controller
{
    public function show($uuid)
    {
        if (!is_string($uuid) || !preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $uuid)) {
            abort(404);
        }

        $route = Route::where('uuid', $uuid)->firstOrFail();

        $route->makeHidden('path');

        $route->load('pictures');
        $route->load('tags');
        $route->load('seasons');
        $route->load('difficulty');
        $route->load('interestPoints');
        $route->load('rates');

        $resume = false;

        if (auth()->check()) {
            $routeHistory = RouteHistory::where('user_id', auth()->id())
                ->where('route_id', $route->id)
                ->get();
            $route->isDone = $routeHistory->count() > 0;

            $route->isFavorite = $route->users->contains(auth()->id());

            $resume = $routeHistory->whereNull('end_timestamp')->count() > 0;
        }

        $route->interestPoints->load('pictures');

        return Inertia::render('Route/Show', [
            'route' => RouteResource::make($route),
            'resume' => $resume,
        ]);
    }

    public function go(string $uuid)
    {
        $route = Route::where('uuid', $uuid)->firstOrFail();
        $route->load('interestPoints');
        $route->load('interestPoints.pictures');

        if (Auth::check()) {
            // check if user has already started the route
            $routeHistory = RouteHistory::where('user_id', Auth::id())
                ->where('route_id', $route->id)
                ->whereNull('end_timestamp')
                ->first();

            if ($routeHistory) {
                return Inertia::render('Route/Go', [
                    'route' => RouteResource::make($route),
                    'resume' => true
                ]);
            }

            // create new route history
            $routeHistory = new RouteHistory();
            $routeHistory->user_id = Auth::id();
            $routeHistory->route_id = $route->id;
            $routeHistory->start_timestamp = now();
            $routeHistory->save();
        }

        return Inertia::render('Route/Go', [
            'route' => RouteResource::make($route)
        ]);
    }
}
