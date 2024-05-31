<?php

namespace App\Http\Controllers;

use App\Http\Requests\RouteRequest;
use App\Http\Resources\RouteResource;
use App\Http\Resources\TagResource;
use App\Models\InterestPoint;
use App\Http\Resources\InterestPointResource;
use App\Http\Resources\DifficultyResource;
use App\Models\Difficulty;
use App\Models\Picture;
use App\Models\Route;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class RouteAdminController extends Controller
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
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Backoffice/Route/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $inerestpoints = InterestPoint::all();
        $inerestpoints->load('pictures');

        return Inertia::render('Backoffice/Route/Create', [
            'tags' => TagResource::collection(Tag::all()),
            'difficulties' => DifficultyResource::collection(Difficulty::all()),
            'interestpoints' => InterestPointResource::collection($inerestpoints),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RouteRequest $request)
    {
        $route = new Route();
        $route->name = $request->title;
        $route->description = $request->description;

        $interestPoints = InterestPoint::find($request->interestpoints);

        $route->start_lat = $interestPoints->first()->lat;
        $route->start_long = $interestPoints->first()->long;
        $route->end_lat = $interestPoints->last()->lat;
        $route->end_long = $interestPoints->last()->long;

        $difficulty = Difficulty::find($request->difficulty_id);
        $route->difficulty()->associate($difficulty);

        $tag = Tag::find($request->tag_id);

        $data = [
            'coordinates' => $interestPoints->map(function ($interestPoint) {
                return [$interestPoint->long, $interestPoint->lat];
            })->toArray()
        ];
        $path = Http::withHeaders([
            'Authorization' => env('OPENROUTESERVICE_API_KEY')
        ])->post('https://api.openrouteservice.org/v2/directions/foot-walking/geojson', $data);

        $resp = $path->json();
        $route->path = json_encode($resp);

        if (!$route->path) {
            return response()->json(['error' => 'Unable to create path'], 500);
        }

        $route->duration = $path->json()['features'][0]['properties']['summary']['duration'];
        $route->length = $path->json()['features'][0]['properties']['summary']['distance'];

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('storage/pictures'), $imageName);

        $picture = new Picture();
        $picture->title = $request->title;
        $picture->description = $request->description;
        $picture->path = $imageName;
        $picture->save();

        $route->save();

        $route->pictures()->attach($picture->id);
        $route->tags()->attach($tag->id);

        $order = 1;
        foreach ($interestPoints as $interestPoint) {
            $route->interestPoints()->attach($interestPoint->id, ['order' => $order++]);
        }

        return redirect()->route('backoffice.routes.show', $route->uuid);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $route = Route::where('uuid', $uuid)->firstOrFail();

        $route->load('pictures');
        $route->load('tags');
        $route->load('seasons');
        $route->load('difficulty');
        $route->load('interestPoints');
        $route->interestPoints->load('pictures');

        return Inertia::render('Backoffice/Route/Show', [
            'route' => RouteResource::make($route),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $route = Route::where('uuid', $uuid)->firstOrFail();
        $inerestpoints = InterestPoint::all();
        $inerestpoints->load('pictures');

        $route->load('pictures');
        $route->load('tags');
        $route->load('seasons');
        $route->load('difficulty');
        $route->load('interestPoints');
        $route->interestPoints->load('pictures');

        return Inertia::render('Backoffice/Route/Edit', [
            'route' => RouteResource::make($route),
            'tags' => TagResource::collection(Tag::all()),
            'difficulties' => DifficultyResource::collection(Difficulty::all()),
            'interestpoints' => InterestPointResource::collection($inerestpoints),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Route::destroy($id);
    }
}
