<?php

namespace App\Http\Controllers;

use App\Http\Resources\InterestPointResource;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Models\Badge;
use App\Models\Picture;
use App\Http\Requests\InterestPointRequest;
use App\Http\Requests\InterestPointUpdateRequest;
use App\Http\Resources\BadgeResource;
use App\Models\InterestPoint;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * Class InterestPointAdminController
 * 
 * This class represents the controller for managing interest points in the admin panel.
 */
class InterestPointAdminController extends Controller
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
        $availableBadge = Badge::whereNull('interest_point_id')
            ->whereNull('route_id')
            ->get();

        // remove if the badge has children
        $availableBadge = $availableBadge->filter(function ($badge) {
            return $badge->children->isEmpty();
        });

        return Inertia::render('Backoffice/InterestPoint/Create', [
            'tags' => TagResource::collection(Tag::all()),
            'badges' => BadgeResource::collection($availableBadge),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\InterestPointRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(InterestPointRequest $request): \Illuminate\Http\RedirectResponse
    {
        $interestPoint = new InterestPoint();

        $interestPoint->name = $request->title;
        $interestPoint->description = $request->description;
        $interestPoint->long = $request->location[0];
        $interestPoint->lat = $request->location[1];

        $tags = Tag::find($request->tags);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('storage/pictures'), $imageName);

        $picture = new Picture();
        $picture->title = $request->title;
        $picture->description = $request->description;
        $picture->path = $imageName;
        $picture->save();

        $interestPoint->save();
        $interestPoint->pictures()->attach($picture->id);
        $interestPoint->tags()->attach($tags);

        // Optional badge
        if ($request->badge_uuid) {
            $badge = Badge::where('uuid', $request->badge_uuid)->firstOrFail();
            $badge->interestPoint()->associate($interestPoint);
            $badge->save();
        }

        return redirect()->route('backoffice.interest-points.show', $interestPoint->uuid);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $uuid
     * @return \Inertia\Response
     */
    public function show(string $uuid): \Inertia\Response
    {
        $ip = InterestPoint::where('uuid', $uuid)->firstOrFail();
        $ip->load('pictures');
        $ip->load('routes');
        $ip->load('badge');
        $ip->load('tags');
        $ip->routes->load('tags');
        $ip->routes->load('pictures');
        return Inertia::render('Backoffice/InterestPoint/Show', [
            'interestpoint' => InterestPointResource::make($ip),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $uuid
     * @return \Inertia\Response
     */
    public function edit(string $uuid): \Inertia\Response
    {
        $ip = InterestPoint::where('uuid', $uuid)->firstOrFail();
        $ip->load('pictures');
        $ip->load('tags');
        $ip->load('badge');
        $availableBadge = Badge::whereNull('interest_point_id')
            ->whereNull('route_id')
            ->get();

        // remove if the badge has children
        $availableBadge = $availableBadge->filter(function ($badge) {
            return $badge->children->isEmpty();
        });

        if ($ip->badge) {
            $availableBadge->prepend($ip->badge);
        }

        return Inertia::render('Backoffice/InterestPoint/Edit', [
            'interestpoint' => InterestPointResource::make($ip),
            'tags' => TagResource::collection(Tag::all()),
            'badges' => BadgeResource::collection($availableBadge),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\InterestPointUpdateRequest  $request
     * @param  string  $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(InterestPointUpdateRequest $request, string $uuid): \Illuminate\Http\RedirectResponse
    {
        $ip = InterestPoint::where('uuid', $uuid)->firstOrFail();

        $ip->name = $request->title;
        $ip->description = $request->description;
        $ip->long = $request->location[0];
        $ip->lat = $request->location[1];

        $tags = Tag::find($request->tags);
        $ip->tags()->detach();
        $ip->tags()->attach($tags);

        if ($ip->badge) {
            $ip->badge->interestPoint()->dissociate();
            $ip->badge->save();
        }
        if ($request->badge_uuid !== null) {
            $badge = Badge::where('uuid', $request->badge_uuid)->firstOrFail();
            $badge->interestPoint()->associate($ip);
            $badge->save();
        }

        if ($request->image !== null) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/pictures'), $imageName);

            $picture = new Picture();
            $picture->title = $request->title;
            $picture->description = $request->description;
            $picture->path = $imageName;
            $picture->save();

            $ip->pictures()->detach();
            $ip->pictures()->attach($picture->id);
        }

        $ip->save();

        if ($ip->routes->count() > 0) {
            $resp = [];
            foreach ($ip->routes as $route) {
                $resp[] = $route->createRoutePath();
            }

            foreach ($resp as $r) {
                if (isset($r['error'])) {
                    return back()->withErrors(['error' => 'Error while creating the route']);
                }
            }
        }

        return redirect()->route('backoffice.interest-points.show', $ip->uuid);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $uuid): \Illuminate\Http\RedirectResponse
    {
        $ip = InterestPoint::where('uuid', $uuid)->firstOrFail();
        $ip->delete();
        return redirect()->route('backoffice.collection');
    }
}
