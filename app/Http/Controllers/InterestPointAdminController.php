<?php

namespace App\Http\Controllers;

use App\Http\Resources\InterestPointResource;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Models\Picture;
use App\Http\Requests\InterestPointRequest;
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
        return Inertia::render('Backoffice/InterestPoint/Create', [
            'tags' => TagResource::collection(Tag::all()),
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

        // TODO: Optional badge
        // $interestPoint->badge = $request->file('badge')->store('/public/badges');

        $imageName = time() . '.' . $request->image->extension();
        $request->image->move(public_path('storage/pictures'), $imageName);

        $picture = new Picture();
        $picture->title = $request->title;
        $picture->description = $request->description;
        $picture->path = $imageName;
        $picture->save();

        $interestPoint->save();
        $interestPoint->pictures()->attach($picture->id);

        return redirect()->route('backoffice.interest-points.show', $interestPoint->uuid);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $ip = InterestPoint::where('uuid', $uuid)->firstOrFail();
        $ip->load('pictures');
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
        return Inertia::render('Backoffice/InterestPoint/Edit', [
            'interestpoint' => InterestPointResource::make($ip),
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
        InterestPoint::destroy($id);
    }
}
