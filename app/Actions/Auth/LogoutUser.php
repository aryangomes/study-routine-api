<?php

namespace App\Actions\Auth;

use App\Models\User;

class LogoutUser
{
    public function __construct()
    {
    }


    public function execute(User $userLogged)
    {
        try {
            $tokenId = $userLogged->currentAccessToken()->id;

            $userLogged->tokens()->where('id', $tokenId)->delete();
        } catch (\Exception $exception) {
            info($exception->getMessage());
        }
    }
}
