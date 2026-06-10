<?php

namespace App\Extensions;

use App\Services\FirebaseService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\GenericUser;

class FirebaseUserProvider implements UserProvider
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function retrieveById($identifier)
    {
        $userData = $this->firebase->getUser($identifier);
        if ($userData) {
            $userData['remember_token'] = $userData['remember_token'] ?? null;
            $user = new \App\Models\User();
            $user->forceFill($userData);
            $user->exists = true;
            return $user;
        }
        return null;
    }

    public function retrieveByToken($identifier, $token)
    {
        return null;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // For simplicity in this NoSQL setup, we just update the Firebase record
        $this->firebase->saveUser($user->getAuthIdentifier(), array_merge($user->toArray(), [
            'remember_token' => $token
        ]));
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) || (count($credentials) === 1 && array_key_exists('password', $credentials))) {
            return null;
        }

        \Illuminate\Support\Facades\Log::info('========== CUSTOMER LOGIN DEBUG ==========');
        \Illuminate\Support\Facades\Log::info('Attempting login for email: ' . ($credentials['email'] ?? 'N/A'));

        $user = $this->firebase->getUserByEmail($credentials['email']);

        if ($user) {
            \Illuminate\Support\Facades\Log::info('User found in Firebase. ID: ' . ($user['id'] ?? 'NO ID') . ', Role: ' . ($user['role'] ?? 'NO ROLE'));
            $user['remember_token'] = $user['remember_token'] ?? null;
            $userModel = new \App\Models\User();
            $userModel->forceFill($user);
            $userModel->exists = true;
            return $userModel;
        }

        \Illuminate\Support\Facades\Log::warning('No user found in Firebase for email: ' . ($credentials['email'] ?? 'N/A'));
        \Illuminate\Support\Facades\Log::info('==========================================');
        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $plain = $credentials['password'] ?? '';
        $hashed = $user->getAuthPassword();
        $result = Hash::check($plain, $hashed);

        \Illuminate\Support\Facades\Log::info('Password check result: ' . ($result ? 'PASS' : 'FAIL') . ' for user ID: ' . $user->getAuthIdentifier());
        \Illuminate\Support\Facades\Log::info('==========================================');

        return $result;
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        return null;
    }
}
