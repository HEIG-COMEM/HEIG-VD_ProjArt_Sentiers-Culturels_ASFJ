<?php

namespace App\Http\Controllers;

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
}
