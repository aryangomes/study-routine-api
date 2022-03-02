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

    public function execute(array $userData): User
    {
        $registeredUser = $this->registerUser->execute($userData);
        return $registeredUser;
    }
}
