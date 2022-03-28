<?php


namespace Domain\Authentication\Actions;

use Domain\User\Models\User;

class CreateTokenToUser
{
    private $tokenName = 'access-token';

    /**
     * Create User's Token
     *
     * @return string
     */
    public function execute(User $userLogged): string
    {

        $accessToken = $userLogged->createToken($this->tokenName)->plainTextToken;

        $userLogged->withAccessToken($accessToken);

        return $accessToken;
    }
}
