<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // 2048 КБ = 2 МБ
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле «Имя» обязательно для заполнения.',
            'name.max' => 'Имя не должно превышать 255 символов.',
            'description.max' => 'Описание не должно превышать 1000 символов.',
            'avatar.image' => 'Файл должен быть изображением.',
            'avatar.mimes' => 'Поддерживаются только JPG, JPEG, PNG, WEBP.',
            'avatar.max' => 'Размер файла не должен превышать 2 МБ.',
        ];
    }
}