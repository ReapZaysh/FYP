<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function create(Request $request)
    {
        if ($request->has('redirect')) {
            session()->put('url.intended', $request->query('redirect'));
        }
        return view('customer.auth.register');
    }

    public function store(Request $request, \App\Services\FirebaseService $firebase): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

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

        $user = new \Illuminate\Auth\GenericUser($userData);
        event(new Registered($user));
        Auth::login($user);

        return redirect()->intended(route('customer.profile', absolute: false));
    }
}
