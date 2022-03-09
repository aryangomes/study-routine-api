<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Crud\RegisterRecordFailException;

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
        } catch (RegisterRecordFailException $exception) {
            logger($exception->getMessage());
            DB::rollBack();
        }

        return $registeredUser;
    }
}
