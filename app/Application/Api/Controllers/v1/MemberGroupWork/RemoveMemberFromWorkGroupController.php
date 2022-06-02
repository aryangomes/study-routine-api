<?php

namespace App\Application\Api\Controllers\v1\MemberGroupWork;

use App\Application\Api\Controllers\v1\GroupWorkController;
use App\Domain\Examables\GroupWork\Member\Models\Member;
use App\Domain\Examables\GroupWork\Member\Services\MemberGroupWorkService;
use App\Domain\Examables\GroupWork\Models\GroupWork;

use Illuminate\Http\Response;

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
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Member not exists in this Group Work');
        }
        // $this->authorize('delete', $member);
        $this->memberGroupWorkService->setGroupWork($groupWork);
        $this->memberGroupWorkService->setMember($member);
        $this->memberGroupWorkService->removeMemberToGroupWork();

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
