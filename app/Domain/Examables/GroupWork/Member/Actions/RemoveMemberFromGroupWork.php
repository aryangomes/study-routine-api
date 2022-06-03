<?php

declare(strict_types=1);

namespace App\Domain\Examables\GroupWork\Member\Actions;

use App\Domain\Examables\GroupWork\Member\Models\Member;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use App\Support\Exceptions\CrudModelOperations\DeleteRecordFailException;
use Illuminate\Support\Facades\DB;

class RemoveMemberFromGroupWork
{
    public function __construct(private Member $member)
    {
    }

    public function __invoke()
    {
        try {
            DB::beginTransaction();

            $this->member->delete();
            DB::commit();
        } catch (DeleteRecordFailException $exception) {
            DB::rollBack();
        }
    }
}
