<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle($gameId)
    {
        $userId = Auth::id();

        $existing = Favorite::where('user_id', $userId)
            ->where('game_id', $gameId)
            ->first();

        if ($existing) {
            $existing->delete();
            $status = 'removed';
        } else {
            Favorite::create([
                'user_id' => $userId,
                'game_id' => $gameId,
            ]);
            $status = 'added';
        }

        return response()->json(['status' => $status]);
    }
}