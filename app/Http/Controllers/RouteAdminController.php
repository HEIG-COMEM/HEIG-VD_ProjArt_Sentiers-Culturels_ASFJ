<?php

namespace App\Http\Controllers;

use App\Http\Requests\RouteRequest;
use App\Http\Requests\RouteUpdateRequest;
use App\Http\Resources\RouteResource;
use App\Http\Resources\TagResource;
use App\Models\InterestPoint;
use App\Http\Resources\InterestPointResource;
use App\Http\Resources\DifficultyResource;
use App\Models\Difficulty;
use App\Models\Picture;
use App\Models\Route;
use App\Models\Tag;
use App\Models\Badge;
use App\Http\Resources\BadgeResource;
use App\Http\Resources\SeasonResource;
use App\Models\Season;
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
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('backoffice.collection');
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @return \Inertia\Response
     */
    public function create(): \Inertia\Response
    {
        $inerestpoints = InterestPoint::all();
        $inerestpoints->load('pictures');

        $availableBadge = Badge::whereNull('interest_point_id')
            ->whereNull('route_id')
            ->get();

        // remove if the badge has children
        $availableBadge = $availableBadge->filter(function ($badge) {
            return $badge->children->isEmpty();
        });

        return Inertia::render('Backoffice/Route/Create', [
            'tags' => TagResource::collection(Tag::all()),
            'difficulties' => DifficultyResource::collection(Difficulty::all()),
            'interestpoints' => InterestPointResource::collection($inerestpoints),
            'badges' => BadgeResource::collection($availableBadge),
            'seasons' => SeasonResource::collection(Season::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param  \App\Http\Requests\RouteRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RouteRequest $request): \Illuminate\Http\RedirectResponse
    {
        $route = new Route();
        $route->name = $request->title;
        $route->description = $request->description;

        $interestPoints = InterestPoint::find($request->interestpoints);

        $route->start_lat = $interestPoints->first()->lat;
        $route->start_long = $interestPoints->first()->long;
        $route->end_lat = $interestPoints->last()->lat;
        $route->end_long = $interestPoints->last()->long;

        $route->length = 0; // Will be calculated later when the route is created
        $route->duration = 0; // Will be calculated later when the route is created

        $difficulty = Difficulty::find($request->difficulty_id);
        $route->difficulty()->associate($difficulty);

        $tags = Tag::find($request->tags);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('storage/pictures'), $imageName);

        $picture = new Picture();
        $picture->title = $request->title;
        $picture->description = $request->description;
        $picture->path = $imageName;
        $picture->save();

        $route->save();

        $route->pictures()->attach($picture->id);
        $route->tags()->attach($tags);

        // Optional badge
        if ($request->badge_uuid) {
            $badge = Badge::where('uuid', $request->badge_uuid)->firstOrFail();
            $badge->route()->associate($route);
            $badge->save();
        }

        $order = 1;
        foreach ($interestPoints as $interestPoint) {
            $route->interestPoints()->attach($interestPoint->id, ['order' => $order++]);
        }

        $resp = $route->createRoutePath(); // Create the route path and calculate the length and duration
        if (isset($resp['error'])) {
            return back()->withErrors(['error' => 'Error while creating the route']);
        }

        // Attach seasons
        $route->seasons()->attach($request->seasons);

        return redirect()->route('backoffice.routes.show', $route->uuid);
    }

    /**
     * Show the form for editing the specified resource.
     * 
     * @param  string  $uuid
     * @return \Inertia\Response
     */
    public function show(string $uuid): \Inertia\Response
    {
        $route = Route::where('uuid', $uuid)->firstOrFail();

        $route->load('pictures', 'tags', 'seasons', 'difficulty', 'badge', 'interestPoints.pictures');

        return Inertia::render('Backoffice/Route/Show', [
            'routeDB' => RouteResource::make($route),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid): \Inertia\Response
    {
        $route = Route::where('uuid', $uuid)->firstOrFail();
        $inerestpoints = InterestPoint::all();
        $inerestpoints->load('pictures');

        $route->load('pictures', 'tags', 'seasons', 'difficulty', 'interestPoints.pictures');

        $route->load('badge');

        $availableBadge = Badge::whereNull('interest_point_id')
            ->whereNull('route_id')
            ->get();

        // remove if the badge has children
        $availableBadge = $availableBadge->filter(function ($badge) {
            return $badge->children->isEmpty();
        });

        if ($route->badge) {
            $availableBadge->prepend($route->badge);
        }

        return Inertia::render('Backoffice/Route/Edit', [
            'route' => RouteResource::make($route),
            'tags' => TagResource::collection(Tag::all()),
            'badges' => BadgeResource::collection($availableBadge),
            'difficulties' => DifficultyResource::collection(Difficulty::all()),
            'interestpoints' => InterestPointResource::collection($inerestpoints),
            'seasons' => SeasonResource::collection(Season::all()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     * 
     * @param  \App\Http\Requests\RouteUpdateRequest  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RouteUpdateRequest $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $route = Route::where('uuid', $id)->firstOrFail();
        $route->name = $request->title;
        $route->description = $request->description;
        $route->difficulty_id = $request->difficulty_id;

        // Picture
        if ($request->image) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/pictures'), $imageName);

            $picture = new Picture();
            $picture->title = $request->title;
            $picture->description = $request->description;
            $picture->path = $imageName;
            $picture->save();

            $route->pictures()->detach();
            $route->pictures()->attach($picture->id);
        }

        // Badge
        if ($request->badge_uuid) {
            $badge = Badge::where('uuid', $request->badge_uuid)->firstOrFail();
            $badge->route()->associate($route);
            $badge->save();
        } else {
            if ($route->badge) {
                $badge = Badge::where('id', $route->badge->id)->firstOrFail();
                $badge->route_id = null;
                $badge->save();
            }
        }

        // Interest points
        $route->interestPoints()->detach();
        $order = 1;
        foreach ($request->interestpoints as $interestPoint) {
            $route->interestPoints()->attach($interestPoint, ['order' => $order++]);
        }

        $interestPoints = Route::where('uuid', $id)->firstOrFail()->interestPoints;
        $route->start_lat = $interestPoints->first()->lat;
        $route->start_long = $interestPoints->first()->long;
        $route->end_lat = $interestPoints->last()->lat;
        $route->end_long = $interestPoints->last()->long;

        // Tags
        $route->tags()->detach();
        $route->tags()->attach($request->tags);

        // Seasons
        $route->seasons()->detach();
        $route->seasons()->attach($request->seasons);

        $route->save();
        $route->createRoutePath(); // Recalculate the route path

        return redirect()->route('backoffice.routes.show', $route->uuid);
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @param  string  $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $uuid): \Illuminate\Http\RedirectResponse
    {
        $route = Route::where('uuid', $uuid)->firstOrFail();
        $route->delete();

        return redirect()->route('backoffice.collection');
    }
}
