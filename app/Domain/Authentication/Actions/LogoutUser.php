<?php

namespace Domain\Authentication\Actions;

use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

class LogoutUser
{
    public function __construct()
    {
    }


    public function __invoke(User $userLogged)
    {
        try {
            DB::beginTransaction();
            $tokenId = $userLogged->currentAccessToken()->id;

            $currentAccessTokenWasDelete = $userLogged->tokens()->where('id', $tokenId)->delete();
        } catch (\Exception $exception) {
        }

        $currentAccessTokenWasDelete ? DB::commit() : DB::rollBack();
    }
}
