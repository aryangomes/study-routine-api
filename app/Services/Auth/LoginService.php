<?php


namespace App\Services\Auth;

use App\Actions\Auth\CreateTokenToUser;
use App\Actions\Auth\Login;
use Illuminate\Http\Response;

class LoginService
{

    public function __construct(
        private Login $login,
        private CreateTokenToUser $createTokenToUser
    ) {
    }

    public function execute(array $dataToLogin)
    {
        $userLogged = $this->login->execute($dataToLogin);

        $this->createTokenToUser->execute($userLogged);

        return $userLogged;
    }
}
