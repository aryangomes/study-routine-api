<?php

namespace App\Application\Api\Controllers\v1;

use App\Application\Api\Requests\Homework\StoreHomeworkRequest;
use App\Application\Api\Requests\Homework\UpdateHomeworkRequest;
use App\Application\Api\Resources\Homework\HomeworkCollection;
use App\Application\Api\Resources\Homework\HomeworkResource;
use App\Domain\Homework\Models\Homework;
use App\Domain\Homework\Services\HomeworkService;
use Domain\Subject\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HomeworkController extends BaseApiController
{

    public function __construct(private HomeworkService $homeworkService)
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
            $collection = $this->homeworkService->getAll();
        } else {
            $collection = $this->homeworkService
                ->getRecordsFilteredByQuery($request);
        }


        return response()->json(new HomeworkCollection($collection));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Application\Api\Requests\Homework\StoreHomeworkRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreHomeworkRequest $request)
    {

        $validatedData = $request->validated();

        $this->authorize('create', [
            HomeWork::class, $validatedData['subject_id']
        ]);

        $homeworkCreated = $this->homeworkService->create($validatedData);

        return response()->json(
            new HomeworkResource($homeworkCreated),
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Domain\Homework\Models\Homework  $homework
     * @return \Illuminate\Http\Response
     */
    public function show(Homework $homework)
    {
        $this->authorize('view', $homework);

        return response()->json(
            new HomeworkResource($homework),
            Response::HTTP_OK
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Application\Api\Requests\Homework\UpdateHomeworkRequest  $request
     * @param  \App\Domain\Homework\Models\Homework  $homework
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateHomeworkRequest $request, Homework $homework)
    {
        $this->authorize('update', $homework);

        $validatedData = $request->validated();

        $homeworkUpdated = $this->homeworkService->update($homework, $validatedData);

        return response()->json(
            new HomeworkResource($homeworkUpdated),
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Domain\Homework\Models\Homework  $homework
     * @return \Illuminate\Http\Response
     */
    public function destroy(Homework $homework)
    {
        $this->authorize('delete', $homework);

        $this->homeworkService->delete($homework);

        return response()->json(
            status: Response::HTTP_NO_CONTENT
        );
    }
}
