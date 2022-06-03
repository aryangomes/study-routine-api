<?php

declare(strict_types=1);

namespace App\Domain\Examables\GroupWork\Member\Actions;

use App\Domain\Examables\GroupWork\Member\Models\Member;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use Illuminate\Support\Facades\DB;
use App\Support\Exceptions\CrudModelOperations\RegisterRecordFailException;

class AddMemberToGroupWork
{
    public function __construct(private GroupWork $groupWork)
    {
    }

    public function __invoke(string $userId)
    {
        try {
            DB::beginTransaction();

            $this->groupWork->members()->create([
                'user_id' => $userId,
            ]);
            DB::commit();
        } catch (RegisterRecordFailException $exception) {
            DB::rollBack();
        }
    }
}
