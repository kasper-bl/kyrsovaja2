<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\Request;

class SuggestionController extends Controller
{
    // Пользователь отправляет заявку
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ], [
            'title.required' => 'Укажите название игры',
            'title.max' => 'Название не должно превышать 255 символов',
        ]);

        Suggestion::create($validated);

        return redirect()->back()->with('suggestion_success', 'Спасибо! Мы рассмотрим вашу заявку.');
    }

    // Админ видит все заявки
    public function index()
    {
        $suggestions = Suggestion::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.suggestions', compact('suggestions'));
    }

    // Админ удаляет заявку
    public function destroy(Suggestion $suggestion)
    {
        $suggestion->delete();
        return redirect()->route('admin.suggestions')->with('success', 'Заявка удалена');
    }
}