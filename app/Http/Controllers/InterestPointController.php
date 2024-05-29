<?php

namespace App\Http\Controllers;

use App\Http\Resources\InterestPointResource;
use Illuminate\Http\Request;
use App\Models\InterestPoint;
use App\Models\RouteHistory;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InterestPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $interestPoints = InterestPoint::paginate(10);

        return Inertia::render('InterestPoint/Index', [
            'interestPoints' => InterestPointResource::collection($interestPoints),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('InterestPoint/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $interestPoint = InterestPoint::where('uuid', $uuid)->firstOrFail();
        $interestPoint->load('pictures');
        $interestPoint->load('routes');
        $interestPoint->routes->load('pictures');
        $interestPoint->routes->load('tags');

        if (Auth::check()) {
            // Load the user's history for the routes
            foreach ($interestPoint->routes as $route) {
                $isDone = RouteHistory::where('user_id', Auth::id())
                    ->where('route_id', $route->id)
                    ->first();

                $route->isDone = $isDone ? true : false;
            }
        }

        return new InterestPointResource($interestPoint);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
        //
    }
}
