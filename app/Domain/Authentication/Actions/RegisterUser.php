<?php

namespace Domain\Authentication\Actions;

use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use App\Support\Exceptions\CrudModelOperations\RegisterRecordFailException;

class RegisterUser
{
    public function __construct()
    {
    }

    public function __invoke(array $userData): null|User
    {
        $registeredUser = null;
        try {
            DB::beginTransaction();

            $registeredUser = User::create($userData);

            DB::commit();
        } catch (RegisterRecordFailException $exception) {

            DB::rollBack();
        }

        return $registeredUser;
    }
}
