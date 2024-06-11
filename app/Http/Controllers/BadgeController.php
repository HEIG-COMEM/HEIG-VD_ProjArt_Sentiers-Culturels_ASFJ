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

        $targetDistance = env('CLOSE_DISTANCE', 0.5);
        if ($distance > $targetDistance) {
            return response()->json([
                'error' => 'Distance too far',
            ]);
        }

        $user = User::find(Auth::id());

        if (!BadgeController::unlock($user, $badge)) {
            return response()->json([
                'message' => 'Badge already claimed',
            ]);
        }

        return response()->json([
            'message' => 'Badge claimed',
        ]);
    }

    public static function unlock(User $user, Badge $badge)
    {
        if ($user->badges->contains($badge->id)) {
            return false;
        }

        $user->badges()->attach($badge->id);
        $user->refresh();

        BadgeController::recursiveUnlock($user, $badge);

        return true;
    }

    private static function recursiveUnlock(User $user, Badge $badge)
    {
        // check if the badge is a parent badge
        if ($badge->parent_id) {
            $parentBadge = Badge::find($badge->parent_id);

            // check if the user has all the child badges
            $children = $parentBadge->children;
            $childrenIds = $children->pluck('id')->toArray();
            $userBadgeIds = $user->badges->pluck('id')->toArray();

            if (count(array_diff($childrenIds, $userBadgeIds)) === 0) {
                BadgeController::unlock($user, $parentBadge);
            }
        }

        return;
    }
}
