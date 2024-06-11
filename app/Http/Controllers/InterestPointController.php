<?php

namespace App\Http\Controllers;

use App\Http\Requests\BadgeClaimRequest;
use App\Http\Resources\InterestPointResource;
use Illuminate\Http\Request;
use App\Models\InterestPoint;
use App\Models\RouteHistory;
use App\Models\User;
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
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $interestPoint = InterestPoint::where('uuid', $uuid)->firstOrFail();
        $interestPoint->load('pictures', 'routes', 'tags', 'badge');
        $interestPoint->routes->load('pictures', 'tags');

        if (Auth::check()) {
            // Load the user's history for the routes
            foreach ($interestPoint->routes as $route) {
                $isDone = RouteHistory::where('user_id', Auth::id())
                    ->where('route_id', $route->id)
                    ->first();

                $route->isDone = $isDone ? true : false;
            }

            if ($interestPoint->badge) {
                $user = User::find(Auth::id());
                $interestPoint->badge->isDone = $user->badges->contains($interestPoint->badge->id);
            }
        }

        return new InterestPointResource($interestPoint);
    }

    public function claimBadge(BadgeClaimRequest $request, string $uuid)
    {
        $interestPoint = InterestPoint::where('uuid', $uuid)->firstOrFail();
        $user = User::find(Auth::id());

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        if (!$interestPoint->badge) {
            return response()->json([
                'message' => 'This interest point does not have a badge',
            ], 404);
        }

        if ($user->badges->contains($interestPoint->badge->id)) {
            return response()->json([
                'message' => 'User already has this badge',
            ], 400);
        }

        $targetDistance = env('CLOSE_DISTANCE', 0.5);
        $distance = GeoLocateController::distance($request->lat, $request->lng, $interestPoint->lat, $interestPoint->long, 'K'); // In km
        if ($distance > $targetDistance) {
            return response()->json(['error' =>
            'Vous êtes trop loin du point pour réclamer son badge.'], 200);
        }

        $user->badges()->attach($interestPoint->badge->id);

        return response()->json([
            'message' => 'Badge claimed',
        ]);
    }
}
