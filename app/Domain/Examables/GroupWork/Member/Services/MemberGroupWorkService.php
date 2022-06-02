<?php

declare(strict_types=1);

namespace App\Domain\Examables\GroupWork\Member\Services;

use App\Domain\Examables\GroupWork\Member\Actions\AddMemberToGroupWork;
use App\Domain\Examables\GroupWork\Member\Actions\RemoveMemberFromGroupWork;
use App\Domain\Examables\GroupWork\Member\Models\Member;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use App\Support\Services\CrudModelOperationsService;
use Illuminate\Database\Eloquent\Collection;

class MemberGroupWorkService extends CrudModelOperationsService
{
    public function __construct(private GroupWork $groupWork, private Member $member)
    {
        parent::__construct($member);
    }

    public function getMembersOfGroupWork(): Collection
    {
        return $this->groupWork->members;
    }


    public function addMemberToGroupWork(string $userId)
    {
        $addMemberToGroupWorkAction = new AddMemberToGroupWork($this->groupWork);

        $addMemberToGroupWorkAction($userId);

        return $this->groupWork;
    }

    public function removeMemberToGroupWork()
    {
        $removeMemberToGroupWorkAction = new RemoveMemberFromGroupWork($this->member);

        $removeMemberToGroupWorkAction();

        return $this->groupWork;
    }

    /**
     * Set the value of groupWork
     *
     * @return  self
     */
    public function setGroupWork($groupWork)
    {
        $this->groupWork = $groupWork;

        return $this;
    }

    /**
     * Set the value of member
     *
     * @return  self
     */
    public function setMember($member)
    {
        $this->member = $member;

        return $this;
    }
}
