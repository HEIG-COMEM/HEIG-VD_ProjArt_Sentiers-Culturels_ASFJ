<?php

namespace App\Http\Controllers;

use App\AchievementEnum;
use App\Models\Badge;
use App\Models\RouteHistory;
use App\Models\Route;
use App\Models\User;

/**
 * Class AchievementController
 * 
 * The AchievementController class is responsible for handling requests related to achievements.
 */
class AchievementController extends Controller
{
    /**
     * Get the number of unique routes completed by a user.
     *
     * @param int $user_id The ID of the user.
     * @return array An array containing the unique routes count, total routes count, percentage, and reward.
     */
    public static function getRoutesCompleted(int $user_id): array
    {
        $uniqueRoutesCount = RouteHistory::where([['user_id', $user_id], ['end_timestamp', '<>', null], ['is_interrupted', false]])->distinct('route_id')->count();
        $totalRoutesCount = Route::count();

        $percentage = $uniqueRoutesCount / $totalRoutesCount * 100;

        $reward = AchievementEnum::getReward($percentage)->toString();

        return [
            'uniqueRoutesCount' => $uniqueRoutesCount,
            'totalRoutesCount' => $totalRoutesCount,
            'percentage' => $percentage,
            'reward' => $reward,
        ];
    }

    /**
     * Get the total distance traveled by a user.
     *
     * @param int $user_id The ID of the user.
     * @param string $unit The unit of distance (default: 'km').
     * @return array An array containing the total distance, total possible distance, and reward.
     */
    public static function getTotalDistance(int $user_id, string $unit = 'km'): array
    {
        $userRoutes = RouteHistory::where([['user_id', $user_id], ['end_timestamp', '<>', null], ['is_interrupted', false]])->get();
        $routes = $userRoutes->map(function ($route) {
            return Route::find($route->route_id);
        });

        $totalPossibleM = Route::sum('length');
        $totalM = $routes->sum('length');

        if ($unit === 'km') {
            $totalM /= 1000;
            $totalPossibleM /= 1000;
        }

        $reward = AchievementEnum::getReward($totalM / $totalPossibleM * 100)->toString();

        return [
            'total' => $totalM,
            'totalPossible' => $totalPossibleM,
            'reward' => $reward,
        ];
    }

    /**
     * Get the total number of interest point badges owned by a user.
     *
     * @param int $user_id The ID of the user.
     * @return array An array containing the visited interest point count, total interest point count, and reward.
     */
    public static function getTotalIPBadgesOwned(int $user_id): array
    {
        $user = User::find($user_id);
        $badges = $user->badges;
        $badges = $badges->filter(function ($badge) {
            return $badge->interest_point_id !== null;
        });

        $totalIPWithBadge = Badge::whereNotNull('interest_point_id')->count();

        $reward = AchievementEnum::getReward($badges->count() / $totalIPWithBadge * 100)->toString();

        return [
            'visitedIPCount' => $badges->count(),
            'totalIPCount' => $totalIPWithBadge,
            'reward' => $reward,
        ];
    }
}
