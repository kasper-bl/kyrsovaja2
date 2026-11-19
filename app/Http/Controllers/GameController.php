<?php

namespace App\Http\Controllers;

use App\Models\Game;

class GameController extends Controller
{
    public function show($id)
    {
        $game = Game::with('categories')->findOrFail($id);
        return view('game.show', compact('game'));
    }

    public function random()
    {
        $game = Game::inRandomOrder()->first();

        if (!$game) {
            return response()->json(['error' => 'Игр пока нет'], 404);
        }

        return response()->json(['id' => $game->id]);
    }
}
