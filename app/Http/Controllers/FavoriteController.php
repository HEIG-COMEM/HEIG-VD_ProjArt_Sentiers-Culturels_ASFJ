<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class FavoriteController extends Controller
{
    public function index()
    {
        if (auth()->guest()) {
            return redirect()->route('login');
        }

        $user = User::find(auth()->id());
        $user->load('routes');

        $user->routes->load('tags');

        $user->routes->makeHidden('path');

        return Inertia::render('Favorite', [
            'routes' => $user->routes,
        ]);
    }

    public function toggle(string $uuid)
    {
        if (auth()->guest()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }


        $user = User::find(auth()->id());
        $route = Route::where('uuid', $uuid)->first();

        if ($user->routes->contains($route)) {
            $user->routes()->detach($route);
        } else {
            $user->routes()->attach($route);
        }

        return response()->json(['message' => 'Success']);
    }
}
