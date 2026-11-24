<?php

namespace App\Http\Controllers;

use App\Services\GameService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private readonly GameService $gameService
    ) {}

    public function index(Request $request): View
    {
        // Собираем только нужные поля из запроса для фильтрации
        $filters = $request->only([
            'search', 
            'categories', 
            'difficulty', 
            'age', 
            'players_min', 
            'players_max', 
            'sort'
        ]);

        // Получаем данные через сервис
        $games = $this->gameService->getFilteredGames($filters)->withQueryString();
        $categories = $this->gameService->getAllCategories();

        return view('home', compact('games', 'categories'));
    }
}