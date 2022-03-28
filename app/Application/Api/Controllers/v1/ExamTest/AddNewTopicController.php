<?php

namespace App\Application\Api\Controllers\v1\ExamTest;

use App\Application\Api\Controllers\v1\ExamTestsController;
use App\Application\Api\Requests\Topic\StoreTopicRequest;
use App\Application\Api\Resources\Test\TestResource;
use Domain\Examables\Test\Models\Test;
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
