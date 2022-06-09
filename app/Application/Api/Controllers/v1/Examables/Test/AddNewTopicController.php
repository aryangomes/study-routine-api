<?php

namespace App\Application\Api\Controllers\v1\Examables\Test;

use App\Application\Api\Requests\Examables\Test\Topic\StoreTopicRequest;
use App\Application\Api\Resources\Examables\Test\TestResource;
use Domain\Examables\Test\Models\Test;
use Illuminate\Http\Response;

class AddNewTopicController extends TestController
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
