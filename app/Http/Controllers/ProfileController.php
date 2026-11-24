<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}

    public function index(): View
    {
        $data = $this->userService->getProfileData(Auth::user());
        
        return view('profile.index', $data);
    }

    public function showEdit(): View
    {
        return view('profile.edit', [
            'user' => Auth::user()
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $this->userService->updateProfile(
            $user,
            $request->only(['name', 'description']),
            $request->file('avatar')
        );

        return redirect()->route('profile.edit')
            ->with('status', 'Профиль успешно сохранён!');
    }
}