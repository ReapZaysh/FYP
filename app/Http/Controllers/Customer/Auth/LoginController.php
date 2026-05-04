<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function create(Request $request)
    {
        // Store the intended redirect URL if provided
        if ($request->has('redirect')) {
            session()->put('url.intended', $request->query('redirect'));
        }

        return view('customer.auth.login');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Redirect to intended (e.g. cart) or fallback to profile
        return redirect()->intended(route('customer.profile', absolute: false));
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.menu');
    }
}
