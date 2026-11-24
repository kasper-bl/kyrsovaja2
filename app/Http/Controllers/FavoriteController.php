<?php

namespace App\Http\Controllers;

use App\Services\FavoriteService;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    public function __construct(
        private readonly FavoriteService $favoriteService
    ) {}

    public function toggle($gameId): JsonResponse
    {
        $status = $this->favoriteService->toggle((int) $gameId);

        return response()->json(['status' => $status]);
    }
}