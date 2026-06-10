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

        $user = $this->firebase->getUserByEmail($credentials['email']);
        if ($user) {
            $user['remember_token'] = $user['remember_token'] ?? null;
            $userModel = new \App\Models\User();
            $userModel->forceFill($user);
            $userModel->exists = true;
            return $userModel;
        }
        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return Hash::check($credentials['password'], $user->getAuthPassword());
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
        return null;
    }
}
