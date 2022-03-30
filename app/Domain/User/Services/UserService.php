<?php

namespace Domain\User\Services;

use App\Support\Services\CrudModelOperationsService;
use Domain\User\Actions\DeleteUserAvatar;
use Domain\User\Actions\UploadUserAvatar;
use Domain\User\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserService extends CrudModelOperationsService
{
    // private UploadUserAvatar $uploadUserAvatar;
    public function __construct(
        private UploadUserAvatar $uploadUserAvatar = new UploadUserAvatar,
        private DeleteUserAvatar $deleteUserAvatar = new DeleteUserAvatar
    ) {
        parent::__construct(new User());
    }

    public function uploadUserAvatar(User $user, ?UploadedFile $userAvatarImage)
    {
        if (isset($userAvatarImage)) {

            ($this->deleteUserAvatar)($user);

            ($this->uploadUserAvatar)($user, $userAvatarImage);
        }
    }
}
