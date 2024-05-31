<?php

namespace App\Http\Controllers;

use App\Http\Requests\InterestPointRequest;
use App\Http\Resources\RouteResource;
use App\Http\Resources\TagResource;
use App\Models\InterestPoint;
use App\Models\Picture;
use App\Models\Route;
use App\Models\Tag;
use Illuminate\Http\Request;
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
        return Inertia::render('Backoffice/Route/Create', [
            'tags' => TagResource::collection(Tag::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InterestPointRequest $request)
    {
        // dd($request->all());
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

        $route->load('pictures');
        $route->load('tags');
        $route->load('seasons');
        $route->load('difficulty');
        $route->load('interestPoints');
        $route->interestPoints->load('pictures');

        return Inertia::render('Backoffice/Route/Edit', [
            'route' => RouteResource::make($route),
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
