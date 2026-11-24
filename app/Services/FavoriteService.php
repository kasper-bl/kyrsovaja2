<?php

namespace App\Services;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class FavoriteService
{
    /**
     * Переключение статуса избранного для игры.
     *
     * @return string 'added' или 'removed'
     */
    public function toggle(int $gameId): string
    {
        $userId = Auth::id();

        $existing = Favorite::where('user_id', $userId)
            ->where('game_id', $gameId)
            ->first();

        if ($existing) {
            $existing->delete();
            return 'removed';
        }

        Favorite::create([
            'user_id' => $userId,
            'game_id' => $gameId,
        ]);

        return 'added';
    }
}