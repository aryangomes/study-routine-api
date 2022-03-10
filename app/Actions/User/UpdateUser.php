<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UpdateUser
{
    public function __construct(private User $user)
    {
    }

    public function __invoke(array $dataToUpdateUser): void
    {
        try {
            DB::beginTransaction();
            $userUpdated = $this->user->update($dataToUpdateUser);
        } catch (\Exception $exception) {
            logger($exception->getMessage());
        }

        $userUpdated ? DB::commit() : DB::rollBack();
    }
}
