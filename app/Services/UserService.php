<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserService
{
    /**
     * Получить данные для страницы профиля.
     */
    public function getProfileData(User $user): array
    {
        return [
            'user'      => $user,
            'favorites' => $user->favorites()->paginate(4),
        ];
    }

    /**
     * Обновить данные профиля и аватар пользователя.
     */
    public function updateProfile(User $user, array $data, ?UploadedFile $avatar = null): void
    {
        $user->name = $data['name'];
        $user->description = $data['description'] ?? null;

        if ($avatar) {
            // 1. Безопасно удаляем старый аватар через Storage, если он есть
            if ($user->avatar) {
                // disk('public') работает с папкой storage/app/public, 
                // которая симлинком связана с public/storage
                Storage::disk('public')->delete($user->avatar);
            }

            // 2. Сохраняем новый файл. 
            // Метод store() автоматически сгенерирует уникальное имя и положит файл в storage/app/public/uploads/avatars
            $path = $avatar->store('uploads/avatars', 'public');
            
            // 3. Записываем новый путь в модель
            $user->avatar = $path;
        }

        $user->save();
    }
}