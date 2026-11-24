<?php

namespace App\Http\Controllers;

use App\Services\GameService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class GameController extends Controller
{
    public function __construct(
        private readonly GameService $gameService
    ) {}

    public function show($id): View
    {
        $game = $this->gameService->show((int) $id);

        return view('game.show', compact('game'));
    }

    public function random(): JsonResponse
    {
        $game = $this->gameService->random();

        if (!$game) {
            return response()->json(['error' => 'Игр пока нет'], 404);
        }

        return response()->json(['id' => $game->id]);
    }
}