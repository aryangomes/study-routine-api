<?php


namespace Domain\User\Actions;

use DB;
use Domain\User\Models\User;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class UploadUserAvatar
{
    public function __construct()
    {
    }

    public function __invoke(User $user, UploadedFile $image)
    {
        try {
            $userAvatarPath = $image->store('public/user_images');

            DB::beginTransaction();

            $user->user_avatar_path = $userAvatarPath;

            $userWasUpdated = $user->save();
        } catch (UploadException $exception) {
        }

        $userWasUpdated ? DB::commit() : DB::rollBack();
    }
}
