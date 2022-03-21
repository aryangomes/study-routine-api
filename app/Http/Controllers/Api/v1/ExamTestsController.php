<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Test\StoreTestRequest;
use App\Http\Requests\Test\UpdateTestRequest;
use App\Http\Resources\Test\TestCollection;
use App\Http\Resources\Test\TestResource;
use App\Models\Examables\Test;
use App\Services\ExamTest\ExamTestService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExamTestsController extends Controller
{

    public function __construct(private ExamTestService $examTestService)
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
        $this->examTestService->delete($test);

        return response(status: Response::HTTP_NO_CONTENT);
    }
}
