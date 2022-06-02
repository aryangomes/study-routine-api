<?php

namespace App\Application\Api\Controllers\v1\MemberGroupWork;

use App\Application\Api\Controllers\v1\GroupWorkController;
use App\Application\Api\Resources\MemberGroupWork\MemberGroupWorkCollection;
use App\Domain\Examables\GroupWork\Member\Services\MemberGroupWorkService;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GetMembersOfGroupWorkController extends GroupWorkController
{
    public function __construct(private MemberGroupWorkService $memberGroupWorkService)
    {
    }

    /**
     * Handle the incoming request.
     *
     * @param  \App\Domain\Examables\GroupWork\Models\GroupWork $groupWork
     * @return \Illuminate\Http\Response
     */
    public function __invoke(GroupWork $groupWork)
    {
        $this->memberGroupWorkService->setGroupWork($groupWork);
        $membersOfGroupWork = $this->memberGroupWorkService->getMembersOfGroupWork();


        return response()->json(
            new MemberGroupWorkCollection($membersOfGroupWork),
            Response::HTTP_OK
        );
    }
}
