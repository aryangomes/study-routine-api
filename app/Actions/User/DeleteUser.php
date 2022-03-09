<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class DeleteUser
{
    public function __construct(
        private User $user
    ) {
    }

    public function __invoke()
    {
        try {

            DB::beginTransaction();
            $userWasDelete = $this->user->delete();
        } catch (\Exception $exception) {
            logger(
                get_class($this),
                [
                    'exception' => $exception
                ]
            );
        }

        $userWasDelete ? DB::commit() : DB::rollBack();
    }
}
