<?php

namespace App\Http\Controllers;

use App\Http\Requests\BadgeClaimRequest;
use Illuminate\Http\Request;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    public function claim(BadgeClaimRequest $request, string $uuid)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Unauthorized',
            ], 401);
        }

        $badge = Badge::where('uuid', $uuid)->firstOrFail();
        $badge->load('route');
        $badge->load('interestPoint');

        $distance = null;
        if ($badge->route) {
            $distance = GeoLocateController::distance(
                $request->lat,
                $request->lng,
                $badge->route->end_lat,
                $badge->route->end_long
            );
        } else {
            $distance = GeoLocateController::distance(
                $request->lat,
                $request->lng,
                $badge->interestPoint->lat,
                $badge->interestPoint->long
            );
        }

        $targetDistance = 100.5; // In km // TODO: Change to 0.5
        if ($distance > $targetDistance) {
            return response()->json([
                'error' => 'Distance too far',
            ]);
        }

        $user = User::find(Auth::id());

        if ($user->badges->contains($badge->id)) {
            return response()->json([
                'message' => 'Badge already claimed',
            ]);
        }

        $user->badges()->attach($badge->id);

        return response()->json([
            'message' => 'Badge claimed',
        ]);
    }
}
