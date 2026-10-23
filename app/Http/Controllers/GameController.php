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
}