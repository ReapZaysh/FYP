<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\RegistrationOtpMail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
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

    public function store(Request $request, \App\Services\FirebaseService $firebase)
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

        // Generate 6-digit OTP
        $otp = sprintf("%06d", mt_rand(100000, 999999));

        // Prepare user data for Firebase
        $userId = (string) Str::uuid();
        $userData = [
            'id' => $userId,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
            'loyalty_points' => 0,
            'created_at' => now()->toIso8601String(),
        ];

        // Cache the data and OTP for 10 minutes
        $cacheKey = 'registration_' . $request->email;
        Cache::put($cacheKey, [
            'otp' => $otp,
            'user_data' => $userData
        ], now()->addMinutes(10));

        // Send Email
        Mail::to($request->email)->send(new RegistrationOtpMail($otp, $request->name));

        // Store email in session to verify on next page
        session()->put('verify_email', $request->email);

        return redirect('/c/verify-otp')->with('success', 'A verification code has been sent to your email.');
    }

    public function verifyOtpForm()
    {
        if (!session()->has('verify_email')) {
            return redirect()->route('customer.register');
        }
        return view('customer.auth.verify-otp');
    }

    public function verifyOtpSubmit(Request $request, \App\Services\FirebaseService $firebase)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $email = session('verify_email');
        if (!$email) {
            return redirect()->route('customer.register')->with('error', 'Session expired. Please register again.');
        }

        $cacheKey = 'registration_' . $email;
        $cachedData = Cache::get($cacheKey);

        if (!$cachedData) {
            return redirect()->route('customer.register')->with('error', 'OTP has expired. Please register again.');
        }

        if ($cachedData['otp'] !== $request->otp) {
            return back()->with('error', 'Invalid verification code. Please try again.');
        }

        // OTP is valid! Save user to Firebase
        $userData = $cachedData['user_data'];
        $firebase->saveUser($userData['id'], $userData);

        // Clear the cache and session
        Cache::forget($cacheKey);
        session()->forget('verify_email');

        // Log the user in
        $user = new \Illuminate\Auth\GenericUser($userData);
        event(new Registered($user));
        Auth::login($user);

        return redirect()->intended(route('customer.profile', absolute: false))->with('success', 'Account verified and created successfully!');
    }

    public function resendOtp()
    {
        $email = session('verify_email');
        if (!$email) {
            return redirect()->route('customer.register');
        }

        $cacheKey = 'registration_' . $email;
        $cachedData = Cache::get($cacheKey);

        if (!$cachedData) {
            return redirect()->route('customer.register')->with('error', 'Registration session expired. Please start over.');
        }

        // Generate new OTP
        $newOtp = sprintf("%06d", mt_rand(100000, 999999));
        
        // Update cache
        $cachedData['otp'] = $newOtp;
        Cache::put($cacheKey, $cachedData, now()->addMinutes(10));

        // Send Email
        Mail::to($email)->send(new RegistrationOtpMail($newOtp, $cachedData['user_data']['name']));

        return back()->with('success', 'A new verification code has been sent to your email.');
    }
}
