<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\InterestPoint;
use App\Http\Resources\InterestPointResource;
use App\Http\Resources\TagResource;
use App\Models\Tag;

/**
 * Class MapController
 * 
 * This class is responsible for handling the logic related to the map.
 */
class MapController extends Controller
{
    /**
     * Display the map view with interest points and tags.
     *
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $IP = InterestPoint::all();
        $IP->load('pictures');
        $IP->load('tags');

        return Inertia::render('Map', [
            'interestPoints' => InterestPointResource::collection($IP),
            'tags' => TagResource::collection(Tag::all()),
        ]);
    }
}
