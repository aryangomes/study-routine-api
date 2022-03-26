<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Requests\Topic\StoreTopicRequest;
use App\Http\Requests\Topic\UpdateTopicRequest;
use App\Http\Resources\Test\TestResource;
use App\Http\Resources\Topic\TopicResource;
use App\Models\Examables\Test;
use App\Models\Topic;
use App\Models\User;
use App\Services\CrudModelOperationsService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TopicController extends BaseApiController
{

    private CrudModelOperationsService $crudModelOperationsService;
    public function __construct()
    {
        $this->crudModelOperationsService = new CrudModelOperationsService(new Topic);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->routeNotImplemented();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTopicRequest $request)
    {
        $this->routeNotImplemented();
    }

    /**
     * Display the specified resource.
     *
     * @param  Topic $topic
     * @return \Illuminate\Http\Response
     */
    public function show(Topic $topic)
    {
        $this->routeNotImplemented();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Topic $topic
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTopicRequest $request, Topic $topic)
    {
        $this->authorize('update', $topic);

        $dataValidated = $request->validated();

        $topicUpdated = $this->crudModelOperationsService->update($topic,   $dataValidated);

        return response()->json(new TestResource($topicUpdated->test));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Topic $topic
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topic $topic)
    {
        $this->authorize('delete', $topic);
        $this->crudModelOperationsService->delete($topic);

        return response()->json(status: Response::HTTP_NO_CONTENT);
    }
}
