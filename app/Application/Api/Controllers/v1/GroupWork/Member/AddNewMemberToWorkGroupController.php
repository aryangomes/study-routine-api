<?php

namespace App\Application\Api\Controllers\v1\GroupWork\Member;

use App\Application\Api\Controllers\v1\GroupWork\GroupWorkController;
use App\Application\Api\Requests\MemberGroupWork\StoreMemberGroupWorkRequest;
use App\Application\Api\Resources\GroupWork\GroupWorkResource;
use App\Domain\Examables\GroupWork\Member\Models\Member;
use App\Domain\Examables\GroupWork\Member\Services\MemberGroupWorkService;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use Illuminate\Http\Response;

class AddNewMemberToWorkGroupController extends GroupWorkController
{

    public function __construct(private MemberGroupWorkService $memberGroupWorkService)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param  \App\Application\Api\Requests\MemberGroupWork\StoreMemberGroupWorkRequest  $request
     * @param  \App\Domain\Examables\GroupWork\Models\GroupWork $groupWork
     * 
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StoreMemberGroupWorkRequest $request, GroupWork  $groupWork)
    {
        $validatedData = $request->validated();

        $this->memberGroupWorkService = new MemberGroupWorkService($groupWork, new Member());

        $groupWorkWithMemberAdded = $this->memberGroupWorkService->addMemberToGroupWork($validatedData['user_id']);

        return response()->json(
            new GroupWorkResource($groupWorkWithMemberAdded),
            Response::HTTP_CREATED
        );
    }
}
