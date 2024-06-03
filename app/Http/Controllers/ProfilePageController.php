<?php

namespace App\Http\Controllers;

use App\Http\Resources\BadgeResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Badge;
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
}
