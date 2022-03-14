<?php

namespace App\Services\Auth;

use App\Actions\Auth\RegisterUser;
use App\Models\User;

/**
 * RegisterUserService
 */
class RegisterUserService
{

    public function __construct(private RegisterUser $registerUser)
    {
    }

    public function registerUser(array $userData): User
    {
        $registerUserAction = $this->registerUser;

        $registeredUser = $registerUserAction($userData);

        return $registeredUser;
    }
}
