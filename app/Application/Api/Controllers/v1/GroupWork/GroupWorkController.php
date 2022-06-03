<?php

namespace App\Application\Api\Controllers\v1\GroupWork;

use App\Application\Api\Controllers\v1\BaseApiController;
use App\Application\Api\Requests\GroupWork\StoreGroupWorkRequest;
use App\Application\Api\Requests\GroupWork\UpdateGroupWorkRequest;
use App\Application\Api\Resources\GroupWork\GroupWorkCollection;
use App\Application\Api\Resources\GroupWork\GroupWorkResource;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use App\Domain\Examables\GroupWork\Services\GroupWorkService;
use Domain\Subject\Models\Subject;
use Illuminate\Http\Response;

class GroupWorkController extends BaseApiController
{
    public function __construct(private GroupWorkService $groupWorkService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = $this->groupWorkService->getAll();

        return response()->json(new GroupWorkCollection($collection));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGroupWorkRequest $request)
    {

        $validatedData = $request->validated();

        $subject = Subject::find($validatedData['subject_id']);

        $this->authorize('createAExam', $subject);

        $groupWorkCreated = $this->groupWorkService->create($validatedData);

        return response()->json(new GroupWorkResource($groupWorkCreated), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  GroupWork $groupWork
     * @return \Illuminate\Http\Response
     */
    public function show(GroupWork $groupWork)
    {
        $this->authorize('view', $groupWork);
        return response()->json(new GroupWorkResource($groupWork));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  GroupWork $groupWork
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGroupWorkRequest $request, GroupWork $groupWork)
    {
        $this->authorize('update', $groupWork);

        $validatedData = $request->validated();

        $groupWorkUpdated = $this->groupWorkService->update($groupWork, $validatedData);

        return response()->json(new GroupWorkResource($groupWorkUpdated));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param   GroupWork $groupWork
     * @return \Illuminate\Http\Response
     */
    public function destroy(GroupWork $groupWork)
    {
        $this->authorize('delete', $groupWork);

        $this->groupWorkService->delete($groupWork);

        return response(status: Response::HTTP_NO_CONTENT);
    }
}
