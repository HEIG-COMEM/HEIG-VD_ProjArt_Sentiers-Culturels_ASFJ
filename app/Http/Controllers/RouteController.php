<?php

namespace App\Http\Controllers;

use App\Http\Requests\RouteCheckEndRequest;
use App\Http\Requests\RouteFinishRequest;
use App\Http\Requests\RouteStartRequest;
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
        $route->load('badge');
        $route->load(['interestPoints.pictures', 'interestPoints.tags', 'interestPoints.badge']);

        if (Auth::check()) {
            $route->interestPoints->each(function ($interestPoint) {
                if ($interestPoint->badge) {
                    $interestPoint->badge->isDone = $interestPoint->badge->users->contains(Auth::id());
                    $interestPoint->badge->makeHidden('users');
                }
            });
        }

        return Inertia::render('Route/Go', [
            'route' => RouteResource::make($route)
        ]);
    }

    public function start(RouteStartRequest $request, string $uuid)
    {
        $route = Route::where('uuid', $uuid)->firstOrFail();
        $targetDistance = 0.5; // In km
        $distance = $this->distance($request->lat, $request->lng, $route->start_lat, $route->start_long, 'K'); // In km
        if ($distance > $targetDistance) {
            return response()->json(['error' =>
            'Vous êtes trop loin du point de départ, veuillez vous en approcher pour commencer le sentier', 'distance' => $distance, 'target' => $targetDistance], 200);
        }

        if (Auth::check()) {
            // check if user has already started the route
            $routeHistory = RouteHistory::where('user_id', Auth::id())
                ->where('route_id', $route->id)
                ->whereNull('end_timestamp')
                ->first();

            if ($routeHistory) {
                return response()->json(['success' => 'Le sentier a déjà été commencé, vous pouvez le reprendre'], 200);
            }

            // create new route history
            $routeHistory = new RouteHistory();
            $routeHistory->user_id = Auth::id();
            $routeHistory->route_id = $route->id;
            $routeHistory->start_timestamp = now();
            $routeHistory->save();
        }

        return response()->json(['success' => 'Le sentier a été commencé'], 200);
    }

    public function checkEnd(RouteCheckEndRequest $request, string $uuid)
    {
        $route = Route::where('uuid', $uuid)->firstOrFail();
        $targetDistance = 1.5; // In km // TODO: Change to 0.5
        $distance = $this->distance($request->lat, $request->lng, $route->end_lat, $route->end_long, 'K'); // In km
        if ($distance > $targetDistance) {
            return response()->json(['error' => 'nok']);
        } else {
            return response()->json(['success' => 'ok']);
        }
    }

    public function interrupt(string $uuid)
    {
        $route = Route::where('uuid', $uuid)->firstOrFail();

        if (Auth::check()) {
            $routeHistory = RouteHistory::where('user_id', Auth::id())
                ->where('route_id', $route->id)
                ->whereNull('end_timestamp')
                ->first();

            if ($routeHistory) {
                $routeHistory->end_timestamp = now();
                $routeHistory->is_interrupted = true;
                $routeHistory->save();
            }
        }

        return response()->json(['success' => 'Le sentier a été interrompu'], 200);
    }

    public function finish(RouteFinishRequest $request, string $uuid)
    {
        $route = Route::where('uuid', $uuid)->firstOrFail();
        $targetDistance = 1.5; // In km //TODO: Change to 0.5
        $distance = $this->distance($request->lat, $request->lng, $route->end_lat, $route->end_long, 'K'); // In km

        $resp = [
            'success' => 'ok',
            'has_won_badge' => false,
        ];

        if ($distance > $targetDistance) {
            return response()->json(['error' =>
            'Vous êtes trop loin du point d\'arrivée, veuillez vous en approcher pour terminer le sentier', 'distance' => $distance, 'target' => $targetDistance], 200);
        }

        if (Auth::check()) {
            $routeHistory = RouteHistory::where('user_id', Auth::id())
                ->where('route_id', $route->id)
                ->whereNull('end_timestamp')
                ->first();

            if ($route->badge) {
                if (!$route->badge->users->contains(Auth::id())) {
                    $route->badge->users()->attach(Auth::id());
                    $resp['has_won_badge'] = true;
                }
            }

            if ($routeHistory) {
                $routeHistory->end_timestamp = now();
                $routeHistory->save();
            } else {
                return response()->json(['error' => 'Le sentier n\'a pas été commencé'], 200);
            }
        }

        return response()->json($resp, 200);
    }

    private function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }
}
