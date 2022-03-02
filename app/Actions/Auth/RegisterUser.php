<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegisterUser
{
    public function __construct()
    {
    }

    public function execute(array $userData): null|User
    {
        $registeredUser = null;
        try {
            DB::beginTransaction();

            $registeredUser = User::create($userData);

            DB::commit();
        } catch (\Exception $exception) {
            info($exception->getMessage());
            DB::rollBack();
        }

        return $registeredUser;
    }
}
