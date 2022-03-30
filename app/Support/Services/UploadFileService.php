<?php

namespace App\Support\Services;

use Domain\User\Actions\UploadUserAvatar;
use Illuminate\Http\UploadedFile;

class UploadFileService
{
    public function __construct(private UploadUserAvatar $uploadUserAvatar)
    {
    }

    public function uploadUserAvatar(UploadedFile $userAvatarImage)
    {
    }
}
