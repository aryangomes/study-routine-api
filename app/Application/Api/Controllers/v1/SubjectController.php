<?php

namespace App\Application\Api\Controllers\v1;

use App\Application\Api\Controllers\Controller;
use App\Application\Api\Requests\Subject\StoreSubjectRequest;
use App\Application\Api\Requests\Subject\UpdateSubjectRequest;
use App\Application\Api\Resources\Subject\SubjectCollection;
use App\Application\Api\Resources\Subject\SubjectResource;
use Domain\Subject\Models\Subject;
use Domain\Subject\Services\SubjectService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class SubjectController extends Controller
{

    public function __construct(private SubjectService $subjectService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        if (empty($request->query())) {
            $collection = $this->subjectService->getAll();
        } else {
            $collection = $this->subjectService
                ->getRecordsFilteredByQuery($request);
        }

        return response()->json(new SubjectCollection($collection));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Application\Api\Requests\Subject\StoreSubjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSubjectRequest $request)
    {

        $validatedData = $request->validated();

        $subjectCreated = $this->subjectService->create($validatedData);

        return response()->json(
            new SubjectResource($subjectCreated),
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \Domain\Subject\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        $this->authorize('view', $subject);

        return response()->json(new SubjectResource($subject));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Application\Api\Requests\Subject\UpdateSubjectRequest  $request
     * @param  \Domain\Subject\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $this->authorize('update', $subject);

        $validatedData = $request->validated();

        $subjectUpdated =
            $this->subjectService->update($subject, $validatedData);

        return response()->json(
            new SubjectResource($subjectUpdated),
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Domain\Subject\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $this->authorize('delete', $subject);

        $this->subjectService->delete($subject);

        return response(status: Response::HTTP_NO_CONTENT);
    }
}
