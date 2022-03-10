<?php

namespace App\Services\User;

use App\Actions\User\DeleteUser;
use App\Actions\User\UpdateUser;
use App\Models\User;

class UserService
{
    private UpdateUser $updateUserAction;
    private DeleteUser $deleteUserAction;

    public function __construct(
        private User $user,

    ) {
        $this->updateUserAction = new UpdateUser($user);
        $this->deleteUserAction = new DeleteUser($user);
    }

    /**
     * Update the user
     *
     * @param array $dataToUpdateUser
     * @return User
     */
    public function updateUser($dataToUpdateUser)
    {
        $updateUserAction = $this->updateUserAction;

        $updateUserAction($dataToUpdateUser);

        return $this->user;
    }

    /**
     * Delete the user
     * @return void
     */
    public function deleteUser()
    {
        $deleteUserAction = $this->deleteUserAction;

        $deleteUserAction();
    }
}
