<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Правила валидации
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле «Имя» обязательно для заполнения.',
            'email.required' => 'Поле «Почта» обязательно для заполнения.',
            'email.email' => 'Введите корректный адрес почты.',
            'email.unique' => 'Пользователь с такой почтой уже зарегистрирован.',
            'password.required' => 'Поле «Пароль» обязательно для заполнения.',
            'password.min' => 'Пароль должен быть не короче 6 символов.',
        ];
    }
}