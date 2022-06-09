<?php

namespace App\Application\Api\Controllers\v1\Examables\Test;

use App\Application\Api\Controllers\Controller;
use App\Application\Api\Resources\Examables\Test\Topic\TopicCollection;
use Domain\Examables\Test\Models\Test;

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
