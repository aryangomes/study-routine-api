<?php

namespace Domain\Authentication\Services;

use Domain\Authentication\Actions\RegisterUser;
use Domain\User\Actions\UploadUserAvatar;
use Domain\User\Models\User;
use Illuminate\Http\UploadedFile;

/**
 * RegisterUserService
 */
class RegisterUserService
{

    public function __construct(
        private RegisterUser $registerUser,
        private UploadUserAvatar $uploadUserAvatar
    ) {
    }

    public function registerNewUser(array $dataToRegistarANewUser, ?UploadedFile $userAvatarImage): User
    {
        $registerUserAction = $this->registerUser;

        $registeredUser = $registerUserAction($dataToRegistarANewUser);

        if (isset($userAvatarImage)) {
            ($this->uploadUserAvatar)($registeredUser, $userAvatarImage);
        }

        return $registeredUser;
    }
}
