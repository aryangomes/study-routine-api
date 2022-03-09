<?php

namespace App\Services\User;

use App\Actions\User\UpdateUser;
use App\Models\User;

class UserService
{
    private UpdateUser $updateUserAction;

    public function __construct(
        private User $user,

    ) {
        $this->updateUserAction = new UpdateUser($user);
    }

    public function updateUser($dataToUpdateUser)
    {
        $this->updateUserAction->execute($dataToUpdateUser);

        return $this->user;
    }
}
