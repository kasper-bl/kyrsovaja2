<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'token' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Поле «Почта» обязательно для заполнения.',
            'email.email' => 'Введите корректный адрес почты.',
            'password.required' => 'Поле «Пароль» обязательно для заполнения.',
            'password.min' => 'Пароль должен быть не короче 6 символов.',
            'password.confirmed' => 'Пароли не совпадают.',
            'token.required' => 'Ссылка для сброса пароля повреждена.',
        ];
    }
}