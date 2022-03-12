<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subject\StoreSubjectRequest;
use App\Http\Requests\Subject\UpdateSubjectRequest;
use App\Http\Resources\Subject\SubjectCollection;
use App\Http\Resources\Subject\SubjectResource;
use App\Models\Subject;
use App\Services\Subject\SubjectService;
use Illuminate\Http\Response;

class SubjectController extends Controller
{

    private SubjectService $subjectService;
    public function __construct()
    {
        $this->subjectService = new SubjectService(new Subject());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = $this->subjectService->getAll();

        return response()->json(new SubjectCollection($collection));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Subject\StoreSubjectRequest  $request
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
     * @param  \App\Models\Subject  $subject
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
     * @param  \App\Http\Requests\Subject\UpdateSubjectRequest  $request
     * @param  \App\Models\Subject  $subject
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
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $this->authorize('delete', $subject);

        $this->subjectService->delete($subject);

        return response(status: Response::HTTP_NO_CONTENT);
    }
}
