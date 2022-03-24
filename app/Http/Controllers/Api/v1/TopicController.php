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
        $this->authorizeResource(Topic::class, 'topic');
    }

    /**
     * Get the map of resource methods to ability names.
     *
     * @return array
     */
    protected function resourceAbilityMap()
    {
        return [
            'index' => 'viewAny',
            'show' => 'view',
            'create' => 'create',
            'store' => 'create',
            'edit' => 'update',
            'update' => 'update',
            'destroy' => 'delete',
        ];
    }

    /**
     * Get the list of resource methods which do not have model parameters.
     *
     * @return array
     */
    protected function resourceMethodsWithoutModels()
    {
        return ['index'];
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
        $dataValidated = $request->validated();
        $testTopic = Test::find($dataValidated['test_id']);
        $this->authorize('create',  $testTopic);
        // $this->authorize('newTopic', $testTopic);

        $topicCreated = $this->crudModelOperationsService->create($dataValidated);

        return response()->json(new TestResource($topicCreated->test), Response::HTTP_CREATED);
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

        return response()->json(new TopicResource($topicUpdated));
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
