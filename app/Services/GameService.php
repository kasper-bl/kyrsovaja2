<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Game;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GameService
{
    /**
     * Получить отфильтрованный и отсортированный список игр с пагинацией.
     */
    public function getFilteredGames(array $filters): LengthAwarePaginator
    {
        $query = Game::query();

        // Поиск по названию
        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%');
        }

        // Фильтр по категориям
        if (!empty($filters['categories']) && is_array($filters['categories'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->whereIn('categories.id', $filters['categories']);
            });
        }

        // Фильтр по сложности
        if (!empty($filters['difficulty']) && is_array($filters['difficulty'])) {
            $query->whereIn('difficulty', $filters['difficulty']);
        }

        // Фильтр по возрасту
        if (!empty($filters['age'])) {
            $query->where('min_age', '<=', (int) $filters['age']);
        }

        // Фильтр по количеству игроков — ЛОГИКА ВКЛЮЧЕНИЯ
        // Игра показывается только если её диапазон игроков ПОЛНОСТЬЮ 
        // находится внутри выбранного пользователем диапазона [players_min, players_max].
        // То есть min_players >= players_min И max_players <= players_max
        if (!empty($filters['players_min']) && !empty($filters['players_max'])) {
            $min = (int) $filters['players_min'];
            $max = (int) $filters['players_max'];

            $query->where('min_players', '>=', $min)
                ->where('max_players', '<=', $max);
        }
        // Сортировка
        $sort = $filters['sort'] ?? 'title';
        match ($sort) {
            'difficulty' => $query->orderByRaw("FIELD(difficulty, 'easy', 'normal', 'hard')"),
            'duration'   => $query->orderBy('duration_minutes', 'asc'),
            'age'        => $query->orderBy('min_age', 'asc'),
            default      => $query->orderBy('title', 'asc'),
        };

        return $query->paginate(6);
    }

    /**
     * Получить все категории для отображения в фильтрах.
     */
    public function getAllCategories()
    {
        return Category::all();
    }

    /**
     * Получить игру по ID с категориями.
     */
    public function show(int $id): Game
    {
        return Game::with('categories')->findOrFail($id);
    }

    /**
     * Получить случайную игру.
     */
    public function random(): ?Game
    {
        return Game::inRandomOrder()->first();
    }
}