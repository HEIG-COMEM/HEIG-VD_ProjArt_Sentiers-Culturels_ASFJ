<?php

namespace App\Http\Controllers;

use App\Http\Resources\BadgeResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Badge;
use Illuminate\Support\Facades\Auth;

/**
 * Class ProfileCollectionController
 * 
 * This class is responsible for handling the user's badge collection.
 */
class ProfileCollectionController extends Controller
{
    /**
     * Display the collection of badges for the user's profile.
     *
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $badges = Badge::whereNull('parent_id')->get();

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
        }

        return Inertia::render('Profile/Collection/Index', [
            'badges' => BadgeResource::collection($badges),
        ]);
    }

    /**
     * Display the details of a specific badge in the user's collection.
     *
     * @param  string  $uuid
     * @return \Inertia\Response
     */
    public function show(string $uuid): \Inertia\Response
    {
        $badge = Badge::where('uuid', $uuid)->firstOrFail();

        $breadcrumb = collect();
        $currentBadge = $badge;
        while ($currentBadge) {
            $breadcrumb->prepend($currentBadge);
            $currentBadge = $currentBadge->parent;
        }

        $badge->load('children');

        if ($badge->children->isEmpty()) {
            $badge->load('interestPoint');
            if ($badge->interestPoint) {
                $badge->interestPoint->load('pictures');
            }

            $badge->load('route');
            if ($badge->route) {
                $badge->route->load('pictures');
            }

            if (Auth::check()) {
                $user = Auth::user();
                $badge->is_owned = $user->badges->contains($badge);
            }
        } else {
            if (Auth::check()) {
                $user = Auth::user();
                $badge->children->each(function ($child) use ($user) {
                    $child->is_owned = $user->badges->contains($child);
                });
            }
        }

        return Inertia::render('Profile/Collection/Show', [
            'badge' => BadgeResource::make($badge),
            'breadcrumb' => BadgeResource::collection($breadcrumb),
        ]);
    }
}
