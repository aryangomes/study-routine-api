<?php


namespace App\Services\Auth;

use App\Actions\Auth\Login;
use Illuminate\Http\Response;

class LoginService
{

    public function __construct(private Login $login)
    {
    }

    public function execute(array $dataToLogin)
    {
        $userLogin = $this->login->execute($dataToLogin);

        return $userLogin;
    }
}
