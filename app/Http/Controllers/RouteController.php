<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Route;

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

        $route->interestPoints->load('pictures');

        return Inertia::render('Route/Show', [
            'route' => $route
        ]);
    }
}
