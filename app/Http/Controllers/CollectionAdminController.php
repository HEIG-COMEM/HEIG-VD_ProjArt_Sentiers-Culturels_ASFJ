<?php

namespace App\Http\Controllers;

use App\Http\Resources\InterestPointResource;
use App\Http\Resources\RouteResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Route;
use App\Models\InterestPoint;

class CollectionAdminController extends Controller
{
    public function index()
    {
        $routes = Route::all();
        $routes->load('difficulty');
        $routes->load('pictures');
        $routes->load('tags');

        $interestpoints = InterestPoint::all();


        return Inertia::render('Backoffice/Collection', [
            'routes' => RouteResource::collection($routes),
            'interestpoints' => InterestPointResource::collection($interestpoints),
        ]);
    }
}
