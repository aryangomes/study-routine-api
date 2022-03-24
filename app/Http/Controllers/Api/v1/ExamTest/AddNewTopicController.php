<?php

namespace App\Http\Controllers\Api\v1\ExamTest;

use App\Http\Controllers\Api\v1\ExamTestsController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Topic\StoreTopicRequest;
use App\Http\Resources\Test\TestResource;
use App\Models\Examables\Test;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AddNewTopicController extends ExamTestsController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(StoreTopicRequest $request, Test $test)
    {
        $validatedData = $request->validated();

        $this->authorize('addNewTopic',  $test);

        $testWithNewTopicCreated = $this->examTestService->addNewTopic($test, $validatedData);

        return response()->json(new TestResource($testWithNewTopicCreated), Response::HTTP_CREATED);
    }
}
