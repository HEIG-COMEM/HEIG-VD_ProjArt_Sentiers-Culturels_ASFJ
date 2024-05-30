<?php

namespace App\Http\Controllers;

use App\Http\Resources\RouteResource;
use App\Models\Route;
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
        return Inertia::render('Backoffice/Route/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
