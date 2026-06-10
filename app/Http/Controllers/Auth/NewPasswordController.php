<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\FirebaseService;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    /**
     * Display the password reset view.
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     * Validates token from Firebase and saves new password to Firebase.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token'    => ['required'],
            'email'    => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Retrieve the stored token record from Firebase
        $record = $this->firebase->getPasswordResetToken($request->email);

        if (!$record) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __('passwords.token')]);
        }

        // Tokens expire after 60 minutes
        $createdAt = \Carbon\Carbon::parse($record['created_at']);
        if ($createdAt->addMinutes(60)->isPast()) {
            $this->firebase->deletePasswordResetToken($request->email);
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __('passwords.token')]);
        }

        // Verify token hash
        if (!hash_equals($record['token'], hash('sha256', $request->token))) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __('passwords.token')]);
        }

        // Token is valid — find the user and update password in Firebase
        $userData = $this->firebase->getUserByEmail($request->email);
        if (!$userData) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => __('passwords.user')]);
        }

        $userData['password']       = Hash::make($request->password);
        $userData['remember_token'] = Str::random(60);
        $this->firebase->saveUser($userData['id'], $userData);

        // Clean up the used token
        $this->firebase->deletePasswordResetToken($request->email);

        // Fire the PasswordReset event using a User model instance
        $user = new \App\Models\User();
        $user->forceFill($userData);
        $user->exists = true;
        event(new PasswordReset($user));

        return redirect()->route('login')->with('status', __('passwords.reset'));
    }
}
