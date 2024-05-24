<?php

namespace App\Http\Controllers;

use App\Http\Resources\InterestPointResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\InterestPoint;

class HomeController extends Controller
{
    public function home()
    {
        $IP = InterestPoint::all();
        return Inertia::render('HomeMobile', [
            'interestPoints' => InterestPointResource::collection($IP),
        ]);
    }
}
