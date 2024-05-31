<?php

namespace App\Http\Controllers;

use App\Http\Resources\InterestPointResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Route;

class HomeController extends Controller
{
    public function home()
    {
        $routes = Route::all();
        $rates = [];
        foreach ($routes as $route) {
            $rates[$route->id] = $route->rates->avg('rate');
        }
        arsort($rates);
        $top3 = array_slice($rates, 0, 3, true);
        $top3 = array_keys($top3);
        $top3 = Route::find($top3)->makeHidden('path')->load('pictures');

        return Inertia::render('Home', [
            'top3' => $top3
        ]);
    }
}
