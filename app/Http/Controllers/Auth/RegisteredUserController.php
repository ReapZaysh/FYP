<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, \App\Services\FirebaseService $firebase): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Check if email already exists in Firebase
        $existingUsers = $firebase->getUserByEmail($request->email);
        if ($existingUsers) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => ['This email is already registered.'],
            ]);
        }

        $userId = (string) \Illuminate\Support\Str::uuid();
        $userData = [
            'id' => $userId,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'loyalty_points' => 0,
            'created_at' => now()->toIso8601String(),
        ];

        $firebase->saveUser($userId, $userData);

        $user = new \App\Models\User();
        $user->forceFill($userData);
        $user->exists = true;

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
