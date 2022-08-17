<?php

namespace App\Application\Api\Controllers\v1\Examables\GroupWork\Member;

use App\Application\Api\Controllers\v1\Examables\GroupWork\GroupWorkController;
use App\Domain\Examables\GroupWork\Member\Models\Member;
use App\Domain\Examables\GroupWork\Member\Services\MemberGroupWorkService;
use App\Domain\Examables\GroupWork\Models\GroupWork;

use Illuminate\Http\Response;
use App\Application\Api\Requests\GroupWork\Member\DeleteMemberGroupWorkRequest;

class RemoveMemberFromWorkGroupController extends GroupWorkController
{
    public function __construct(private MemberGroupWorkService $memberGroupWorkService)
    {
    }
    /**
     * Handle the incoming request.
     *
     * @param  @param  \App\Domain\Examables\GroupWork\Models\GroupWork $groupWork
     * @return \Illuminate\Http\Response
     */
    public function __invoke(GroupWork $groupWork, int $memberId)
    {
        $member = Member::find($memberId);


        if (!$member) {
            abort(Response::HTTP_NOT_FOUND, __('member.remove_member_errors.member_not_found'));
        }

        if ($member->group_work_id !== $groupWork->id) {
            abort(Response::HTTP_FORBIDDEN, __('member.remove_member_errors.member_does_not_belong_to_this_group'));
        }

        if ($member->isOwnerOfGroupWork) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, __('member.remove_member_errors.member_is_owner'));
        }
        $this->authorize('delete', $member);

        $this->memberGroupWorkService->setGroupWork($groupWork);
        $this->memberGroupWorkService->setMember($member);
        $this->memberGroupWorkService->removeMemberToGroupWork();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
