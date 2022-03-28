<?php


namespace Domain\Authentication\Services;

use Domain\Authentication\Actions\CreateTokenToUser;
use Domain\Authentication\Actions\Login;

class LoginService
{

    public function __construct(
        private Login $login,
        private CreateTokenToUser $createTokenToUser
    ) {
    }

    public function __invoke(array $dataToLogin)
    {
        $loginAction = $this->login;

        $userLogged = $loginAction($dataToLogin);

        $this->createTokenToUser->execute($userLogged);

        return $userLogged;
    }
}
