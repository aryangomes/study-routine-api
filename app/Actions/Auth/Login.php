<?php

namespace App\Actions\Auth;

use App\Models\User;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class Login
{
    public function __invoke(array $dataToLogin): User
    {
        $user = User::where('email', $dataToLogin['email'])->first();

        $this->userCanLogin($user, $dataToLogin);

        return $user;
    }

    private function userCanLogin($user, $dataToLogin)
    {
        if (!$user || !Hash::check($dataToLogin['password'], $user->password)) {
            if (!$user) {
                throw ValidationException::withMessages([
                    'email' => [__('auth.failed')],

                ])->status(Response::HTTP_UNPROCESSABLE_ENTITY);
            } else {
                throw ValidationException::withMessages([
                    'password' => [__('auth.password')],

                ])->status(Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }
    }
}
