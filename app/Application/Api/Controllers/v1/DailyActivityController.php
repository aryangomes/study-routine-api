<?php

namespace App\Application\Api\Controllers\v1;

use App\Application\Api\Requests\DailyActivity\StoreDailyActivity;
use App\Application\Api\Requests\DailyActivity\UpdateDailyActivity;
use App\Application\Api\Resources\DailyActivity\DailyActivityCollection;
use App\Application\Api\Resources\DailyActivity\DailyActivityResource;
use App\Domain\DailyActivity\Models\DailyActivity;
use App\Domain\DailyActivity\Services\DailyActivityService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DailyActivityController extends BaseApiController
{

    public function __construct(private DailyActivityService $dailyActivityService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = $this->dailyActivityService->getAll();

        return response()->json(new DailyActivityCollection($collection));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Application\Api\Requests\DailyActivity\StoreDailyActivity  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDailyActivity $request)
    {
        $this->authorize('create', new DailyActivity());

        $validatedData = $request->validated();

        $homeworkCreated = $this->dailyActivityService->create($validatedData);

        return response()->json(
            new DailyActivityResource($homeworkCreated),
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Domain\DailyActivity\Models\DailyActivity $dailyActivity
     * @return \Illuminate\Http\Response
     */
    public function show(DailyActivity $dailyActivity)
    {
        $this->authorize('view', $dailyActivity);

        return response()->json(
            new DailyActivityResource($dailyActivity),
            Response::HTTP_OK
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Application\Api\Requests\DailyActivity\UpdateDailyActivity;  $request
     * @param  \App\Domain\DailyActivity\Models\DailyActivity $dailyActivity
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDailyActivity $request, DailyActivity $dailyActivity)
    {
        $this->authorize('update', $dailyActivity);

        $validatedData = $request->validated();

        $dailyActivityUpdated = $this->dailyActivityService->update($dailyActivity, $validatedData);

        return response()->json(
            new DailyActivityResource($dailyActivityUpdated),
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Domain\DailyActivity\Models\DailyActivity $dailyActivity
     * @return \Illuminate\Http\Response
     */
    public function destroy(DailyActivity $dailyActivity)
    {
        $this->authorize('delete', $dailyActivity);

        $this->dailyActivityService->delete($dailyActivity);

        return response()->json(
            status: Response::HTTP_NO_CONTENT
        );
    }
}
