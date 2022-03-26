<?php

namespace App\Http\Controllers\Api\v1\ExamTest;

use App\Http\Controllers\Controller;
use App\Http\Resources\Topic\TopicCollection;
use App\Models\Examables\Test;

class GetTopicsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Test $test)
    {
        $this->authorize('getAnyTopic', $test);
        $testsTopics = $test->topics;

        return response()->json(new TopicCollection($testsTopics));
    }
}
