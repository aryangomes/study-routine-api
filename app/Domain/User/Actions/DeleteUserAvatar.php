<?php

namespace Domain\User\Actions;

use Domain\User\Models\User;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\FilesystemException;

class DeleteUserAvatar
{

    public function __construct()
    {
    }

    public function __invoke(User $user): bool
    {
        $userAvatarWasDelete = false;

        throw_if(is_null($user->user_avatar_path), new FileNotFoundException());

        try {
            $userAvatarWasDelete = Storage::delete($user->user_avatar_path);
        } catch (FilesystemException $exception) {
        }

        return $userAvatarWasDelete;
    }
}
