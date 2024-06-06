<?php

namespace App\Http\Controllers;

use App\AchievementEnum;
use App\Models\RouteHistory;
use App\Models\Route;
use App\Models\User;

class AchievementController extends Controller
{
    public static function getRoutesCompleted(int $user_id)
    {
        $uniqueRoutesCount = RouteHistory::where('user_id', $user_id)->distinct('route_id')->count();
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

    public static function getTotalDistance(int $user_id, string $unit = 'km')
    {
        $userRoutes = RouteHistory::where('user_id', $user_id)->get();
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
}
