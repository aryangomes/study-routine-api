<?php

namespace App\Application\Api\Controllers\v1\Examables\Test;

use App\Application\Api\Controllers\Controller;
use App\Application\Api\Requests\Examables\Test\StoreTestRequest;
use App\Application\Api\Requests\Examables\Test\UpdateTestRequest;
use App\Application\Api\Resources\Examables\Test\TestCollection;
use App\Application\Api\Resources\Examables\Test\TestResource;
use Domain\Examables\Test\Models\Test;
use Domain\Subject\Models\Subject;
use Domain\Examables\Test\Services\ExamTestService;
use Illuminate\Http\Response;

class TestController extends Controller
{

    public function __construct(protected ExamTestService $examTestService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = $this->examTestService->getAll();

        return response()->json(new TestCollection($collection));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTestRequest $request)
    {
        $validatedData = $request->validated();
        $subject = Subject::find($validatedData['subject_id']);
        $this->authorize('createAExam', $subject);

        $testCreated = $this->examTestService->create($validatedData);

        return response()->json(new TestResource($testCreated), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Test $test)
    {

        $this->authorize('view', $test);
        return response()->json(new TestResource($test));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Test $test
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTestRequest $request, Test $test)
    {
        $this->authorize('update', $test);

        $validatedData = $request->validated();

        $testUpdate = $this->examTestService->update($test, $validatedData);

        return response()->json(new TestResource($testUpdate));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Test $test
     * @return \Illuminate\Http\Response
     */
    public function destroy(Test $test)
    {
        $this->authorize('delete', $test);
        $this->examTestService->delete($test);

        return response(status: Response::HTTP_NO_CONTENT);
    }
}
