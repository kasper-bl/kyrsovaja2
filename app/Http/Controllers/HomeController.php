<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Game::query();

        // Поиск по названию
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Фильтр по категориям (тип)
        if ($request->filled('categories') && is_array($request->categories)) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->whereIn('categories.id', $request->categories);
            });
        }

        // Фильтр по сложности
        if ($request->filled('difficulty') && is_array($request->difficulty)) {
            $query->whereIn('difficulty', $request->difficulty);
        }

        // Фильтр по возрасту (минимальный возраст не больше выбранного)
        if ($request->filled('age')) {
            $query->where('min_age', '<=', $request->age);
        }

        // Фильтр по количеству игроков (диапазон)
        if ($request->filled('players_min') && $request->filled('players_max')) {
            $min = (int) $request->players_min;
            $max = (int) $request->players_max;
            $query->where(function ($q) use ($min, $max) {
                // Игра подходит, если её диапазон игроков пересекается с выбранным
                $q->where('min_players', '<=', $max)
                  ->where('max_players', '>=', $min);
            });
        }

        // Сортировка
        switch ($request->get('sort')) {
            case 'difficulty':
                $query->orderByRaw("FIELD(difficulty, 'easy', 'normal', 'hard')");
                break;
            case 'duration':
                $query->orderBy('duration_minutes', 'asc');
                break;
            case 'age':
                $query->orderBy('min_age', 'asc');
                break;
            case 'title':
            default:
                $query->orderBy('title', 'asc');
                break;
        }

        $games = $query->paginate(6)->withQueryString();
        $categories = Category::all();

        return view('home', compact('games', 'categories'));
    }
}