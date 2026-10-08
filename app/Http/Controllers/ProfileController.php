<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Показать форму редактирования профиля
    public function showEdit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Сохранить изменения
    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        // Обновляем имя и описание
        $user->name = $request->name;
        $user->description = $request->description;

        // Если загружен новый аватар — сохраняем
        if ($request->hasFile('avatar')) {
            // Удаляем старый файл, если он есть
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            // Генерируем уникальное имя файла
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();

            // Сохраняем файл в public/uploads/avatars/
            $file->move(public_path('uploads/avatars'), $filename);

            // Записываем путь в БД (относительно public/)
            $user->avatar = 'uploads/avatars/' . $filename;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Профиль успешно сохранён!');
    }
}