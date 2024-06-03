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
     */
    public function index()
    {
        return redirect()->route('backoffice.collection');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
     */
    public function store(InterestPointRequest $request)
    {
        $interestPoint = new InterestPoint();

        $interestPoint->name = $request->title;
        $interestPoint->description = $request->description;
        $interestPoint->long = $request->location[0];
        $interestPoint->lat = $request->location[1];

        // TODO: Implement tags in DB
        // $tag = Tag::find($request->tag_id);
        // $interestPoint->tags()->associate($tag);

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('storage/pictures'), $imageName);

        $picture = new Picture();
        $picture->title = $request->title;
        $picture->description = $request->description;
        $picture->path = $imageName;
        $picture->save();

        $interestPoint->save();
        $interestPoint->pictures()->attach($picture->id);

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
     */
    public function show(string $uuid)
    {
        $ip = InterestPoint::where('uuid', $uuid)->firstOrFail();
        $ip->load('pictures');
        $ip->load('routes');
        $ip->load('badge');
        $ip->routes->load('tags');
        $ip->routes->load('pictures');
        return Inertia::render('Backoffice/InterestPoint/Show', [
            'interestpoint' => InterestPointResource::make($ip),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $ip = InterestPoint::where('uuid', $uuid)->firstOrFail();
        $ip->load('pictures');
        // TODO: Implement tags in DB
        // $ip->load('tags');
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
     */
    public function update(InterestPointUpdateRequest $request, string $uuid)
    {
        $ip = InterestPoint::where('uuid', $uuid)->firstOrFail();

        $ip->name = $request->title;
        $ip->description = $request->description;
        $ip->long = $request->location[0];
        $ip->lat = $request->location[1];


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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $ip = InterestPoint::where('uuid', $uuid)->firstOrFail();
        $ip->delete();
        return redirect()->route('backoffice.collection');
    }
}
