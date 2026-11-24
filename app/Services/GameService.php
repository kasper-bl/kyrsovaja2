<?php

namespace App\Services;

use App\Models\Game;

class GameService
{
    /**
     * Получить игру по ID с категориями.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function show(int $id): Game
    {
        return Game::with('categories')->findOrFail($id);
    }

    /**
     * Получить случайную игру.
     *
     * @return Game|null
     */
    public function random(): ?Game
    {
        return Game::inRandomOrder()->first();
    }
}