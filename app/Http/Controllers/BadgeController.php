<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    public function claim(string $uuid)
    {
        if (!Auth::check()) {
            return response()->json([
                'error' => 'Unauthorized',
            ], 401);
        }

        $badge = Badge::where('uuid', $uuid)->firstOrFail();
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
