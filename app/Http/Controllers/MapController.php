<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\InterestPoint;
use App\Http\Resources\InterestPointResource;
use App\Http\Resources\PathResource;
use App\Http\Resources\RouteResource;
use App\Http\Resources\TagResource;
use App\Models\Route;
use App\Models\Tag;

class MapController extends Controller
{
    public function index()
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
