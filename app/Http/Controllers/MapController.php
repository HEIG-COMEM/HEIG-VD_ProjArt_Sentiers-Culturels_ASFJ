<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\InterestPoint;
use App\Http\Resources\InterestPointResource;
use App\Http\Resources\PathResource;
use App\Http\Resources\RouteResource;
use App\Models\Route;

class MapController extends Controller
{
    public function index()
    {
        $IP = InterestPoint::all();
        $routes = Route::where('path', '!=', null)->get();

        return Inertia::render('Map', [
            'interestPoints' => InterestPointResource::collection($IP),
            'routes' => RouteResource::collection($routes),
        ]);
    }
}
