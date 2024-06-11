<?php

namespace App\Http\Controllers;

use App\Http\Resources\InterestPointResource;
use App\Http\Resources\TagResource;
use App\Models\InterestPoint;
use App\Models\Tag;
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
        $interestPoints = InterestPoint::all();
        $interestPoints->load('tags', 'pictures');
        return Inertia::render('Backoffice/Map', [
            'interestpoints' => InterestPointResource::collection($interestPoints),
            'tags' => TagResource::collection(Tag::all())
        ]);
    }
}
