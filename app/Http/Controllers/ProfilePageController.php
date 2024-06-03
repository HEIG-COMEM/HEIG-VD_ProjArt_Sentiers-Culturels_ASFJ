<?php

namespace App\Http\Controllers;

use App\Http\Resources\BadgeResource;
use App\Http\Resources\RouteHistoryResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Badge;
use App\Models\RouteHistory;
use Illuminate\Support\Facades\Auth;

class ProfilePageController extends Controller
{
    public function index()
    {
        $badges = Badge::whereNull('parent_id')->take(6)->get();

        $badges->each(function ($badge) {
            $badge->children_count = $badge->children->count();
        });

        if (Auth::check()) {
            $user = Auth::user();
            // For each badge count how many of its children are owned by the user
            $badges->each(function ($badge) use ($user) {
                $badge->owned_children_count = $badge->children->filter(function ($child) use ($user) {
                    return $user->badges->contains($child);
                })->count();
            });

            $badges->each(function ($badge) use ($user) {
                $badge->is_owned = $user->badges->contains($badge);
            });
        }
        return Inertia::render('Profile/Index', [
            'collection' => BadgeResource::collection($badges),
        ]);
    }

    public function history()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userHistory = RouteHistory::where('user_id', Auth::id())->orderBy('start_timestamp', 'desc')->get();
        $userHistory->load('route');
        $userHistory->load(['route.pictures', 'route.tags']);

        return Inertia::render('Profile/History', [
            'histories' => RouteHistoryResource::collection($userHistory),
        ]);
    }
}
