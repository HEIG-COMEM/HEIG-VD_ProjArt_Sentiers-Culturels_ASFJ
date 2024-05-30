<?php

namespace App\Http\Controllers;

use App\Http\Resources\RouteResource;
use App\Http\Resources\InterestPointResource;
use App\Models\Route;
use App\Models\InterestPoint;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MapAdminController extends Controller
{
    /**
     * Protected by the admin middleware
     */
    public static function middleware(): array
    {
        return [
            'admin'
        ];
    }

    /**
     * Display the map for the admin
     */
    public function index()
    {
        $routes = Route::all();
        $interestPoints = InterestPoint::all();
        return Inertia::render('Backoffice/Map', [
            'routes' => RouteResource::collection($routes),
            'interestpoints' => InterestPointResource::collection($interestPoints),
        ]);
    }
}
