<?php

namespace Domain\Authentication\Services;

use Domain\Authentication\Actions\RegisterUser;
use Domain\User\Models\User;

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
