<?php

namespace App\Http\Controllers;

use App\Http\Resources\DifficultyResource;
use App\Http\Resources\InterestPointResource;
use App\Http\Resources\RouteResource;
use App\Http\Resources\TagResource;
use App\Models\Difficulty;
use App\Models\Tag;
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
        $interestpoints->load('pictures');

        $difficulties = Difficulty::all();
        $tags = Tag::all();


        return Inertia::render('Backoffice/Collection', [
            'routes' => RouteResource::collection($routes),
            'interestpoints' => InterestPointResource::collection($interestpoints),
            'difficulties' => DifficultyResource::collection($difficulties),
            'tags' => TagResource::collection($tags),
        ]);
    }
}
