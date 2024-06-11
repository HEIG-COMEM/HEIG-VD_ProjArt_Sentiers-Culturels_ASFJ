<?php

namespace App\Http\Controllers;

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
 * Class CollectionAdminController
 * 
 * The CollectionAdminController class is responsible for handling requests related to the collection administration.
 */
class CollectionAdminController extends Controller
{
    /**
     * Display the collection administration page.
     *
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $routes = Route::all();
        $routes->load('difficulty');
        $routes->load('pictures');
        $routes->load('tags');

        $interestpoints = InterestPoint::all();
        $interestpoints->load('pictures');
        $interestpoints->load('tags');

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
