<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Поле «Почта» обязательно для заполнения.',
            'email.email' => 'Введите корректный адрес почты.',
            'password.required' => 'Поле «Пароль» обязательно для заполнения.',
        ];
    }
}