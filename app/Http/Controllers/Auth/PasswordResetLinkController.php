<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Services\FirebaseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     * Stores the token in Firebase instead of the SQLite password_reset_tokens table.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check the user exists in Firebase
        $user = $this->firebase->getUserByEmail($request->email);
        if (!$user) {
            // Return same success message to avoid email enumeration
            return back()->with('status', __('passwords.sent'));
        }

        // Generate a plain token and store its hash in Firebase
        $token = Str::random(64);
        $this->firebase->savePasswordResetToken($request->email, $token);

        // Build the reset URL
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $request->email,
        ], false));

        // Send email
        \Illuminate\Support\Facades\Mail::to($request->email)
            ->send(new PasswordResetMail($resetUrl, $user['name'] ?? 'User'));

        return back()->with('status', __('passwords.sent'));
    }
}
