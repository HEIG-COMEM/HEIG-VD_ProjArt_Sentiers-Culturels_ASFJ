<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ProfilePartialUpdate;
use App\Http\Resources\AchievementResource;
use App\Http\Resources\BadgeResource;
use App\Http\Resources\RouteHistoryResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Badge;
use App\Models\RouteHistory;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProfilePageController
 * 
 * This class is responsible for handling the user's profile page.
 */
class ProfilePageController extends Controller
{
    /**
     * Display the profile page.
     *
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $badges = Badge::whereNull('parent_id')->take(6)->get();

        $badges->each(function ($badge) {
            $badge->children_count = $badge->children->count();
        });

        $badges = $badges->sortByDesc('children_count');

        if (Auth::check()) {
            $user = Auth::user();

            $badges->each(function ($badge) use ($user) {
                $badge->owned_children_count = $badge->children->filter(function ($child) use ($user) {
                    return $user->badges->contains($child);
                })->count();
            });

            $badges->each(function ($badge) use ($user) {
                $badge->is_owned = $user->badges->contains($badge);
            });

            $routeCompletion = AchievementController::getRoutesCompleted($user->id);
            $distance = AchievementController::getTotalDistance($user->id);
            $IPCompletion = AchievementController::getTotalIPBadgesOwned($user->id);
        }

        return Inertia::render('Profile/Index', [
            'collection' => BadgeResource::collection($badges),
            'routeCompletion' => AchievementResource::make($routeCompletion),
            'distance' => AchievementResource::make($distance),
            'IPCompletion' => AchievementResource::make($IPCompletion),
        ]);
    }

    /**
     * Display the user's route history.
     *
     * @return \Inertia\Response|\Illuminate\Http\RedirectResponse
     */
    public function history(): \Inertia\Response|\Illuminate\Http\RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userHistory = RouteHistory::where('user_id', Auth::id())->whereNotNull('end_timestamp')->orderBy('start_timestamp', 'desc')->get();

        $userHistory->load('route');
        $userHistory->load(['route.pictures', 'route.tags']);

        return Inertia::render('Profile/History', [
            'histories' => RouteHistoryResource::collection($userHistory),
        ]);
    }

    /**
     * Update the user's profile.
     *
     * @param  \App\Http\Requests\Auth\ProfilePartialUpdate  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfilePartialUpdate $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $request->user()->update($request->all());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating your profile.');
        }

        return redirect()->route('profile.account');
    }
}
