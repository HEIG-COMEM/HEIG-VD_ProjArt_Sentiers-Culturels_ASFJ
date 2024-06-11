<?php

namespace App\Http\Controllers;

use App\Http\Requests\BadgeClaimRequest;
use Illuminate\Http\JsonResponse;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class BadgeController
 * 
 * This class is responsible for handling badge-related operations.
 */
class BadgeController extends Controller
{
    /**
     * Claim a badge for the authenticated user.
     *
     * @param BadgeClaimRequest $request The request object containing the badge claim data.
     * @param string $uuid The UUID of the badge to be claimed.
     * @return JsonResponse The JSON response indicating the result of the badge claim.
     */
    public function claim(BadgeClaimRequest $request, string $uuid): JsonResponse
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

    /**
     * Unlock a badge for the specified user.
     *
     * @param User $user The user for whom to unlock the badge.
     * @param Badge $badge The badge to be unlocked.
     * @return bool True if the badge was successfully unlocked, false otherwise.
     */
    public static function unlock(User $user, Badge $badge): bool
    {
        if ($user->badges->contains($badge->id)) {
            return false;
        }

        $user->badges()->attach($badge->id);
        $user->refresh();

        BadgeController::recursiveUnlock($user, $badge);

        return true;
    }

    /**
     * Recursively unlock parent badges if all child badges are unlocked.
     *
     * @param User $user The user for whom to unlock the badges.
     * @param Badge $badge The badge to be checked for unlocking its parent badges.
     * @return void
     */
    private static function recursiveUnlock(User $user, Badge $badge): void
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
