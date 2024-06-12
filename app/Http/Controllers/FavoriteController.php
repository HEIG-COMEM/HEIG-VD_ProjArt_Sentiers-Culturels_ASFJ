<?php

namespace App\Http\Controllers;

use App\Http\Resources\RouteResource;
use App\Models\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

/**
 * Class FavoriteController
 *
 * This class is responsible for handling favorite-related operations.
 */
class FavoriteController extends Controller
{
    /**
     * Display the favorite routes for the authenticated user.
     */
    public function index()
    {
        if (auth()->guest()) {
            return redirect()->route('login');
        }

        $user = User::find(auth()->id());
        $user->load('routes');

        $user->routes->load('tags');
        $user->routes->load('pictures');
        $user->routes->makeHidden('path');

        $user->routes->map(function ($route) use ($user) {
            $route->isDone = $user->routesHistory->contains('id', $route->id);
        });

        return Inertia::render('Favorite', [
            'routes' => RouteResource::collection($user->routes)
        ]);
    }

    /**
     * Toggle the favorite status of a route for the authenticated user.
     *
     * @param  string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(string $uuid): \Illuminate\Http\JsonResponse
    {
        if (auth()->guest()) {
            return response()->json(['message' => 'Vous devez être connecté pour ajouter une route en favori'], 401);
        }

        $user = User::find(auth()->id());
        $route = Route::where('uuid', $uuid)->first();

        if ($user->routes->contains($route)) {
            $user->routes()->detach($route);
        } else {
            $user->routes()->attach($route);
        }

        return response()->json(['message' => 'Route ajoutée en favori']);
    }
}
