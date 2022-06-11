<?php

namespace App\Application\Api\Controllers\v1\Examables;

use App\Application\Api\Controllers\v1\BaseApiController;
use App\Application\Api\Requests\Examables\Essay\StoreEssayRequest;
use App\Application\Api\Requests\Examables\Essay\UpdateEssayRequest;
use App\Application\Api\Resources\Examables\Essay\EssayCollection;
use App\Application\Api\Resources\Examables\Essay\EssayResource;
use App\Domain\Examables\Essay\Models\Essay;
use App\Domain\Examables\Essay\Services\EssayService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EssayController extends BaseApiController
{
    public function __construct(private EssayService $essayService)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collection = $this->essayService->getAll();

        return response()->json(new EssayCollection($collection));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Application\Api\Requests\Examables\Essay\StoreEssayRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEssayRequest $request)
    {
        $this->authorize('create', new Essay());

        $validatedData = $request->validated();

        $essayCreated = $this->essayService->create($validatedData);

        return response()->json(
            new EssayResource($essayCreated),
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Domain\Examables\Essay\Models\Essay  $essay
     * @return \Illuminate\Http\Response
     */
    public function show(Essay $essay)
    {
        $this->authorize('view', $essay);

        return response()->json(
            new EssayResource($essay),
            Response::HTTP_OK
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Application\Api\Requests\Examables\Essay\UpdateEssayRequest $request
     * @param  \App\Domain\Examables\Essay\Models\Essay  $essay
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEssayRequest $request, Essay $essay)
    {
        $this->authorize('update', $essay);

        $validatedData = $request->validated();

        $essayUpdated = $this->essayService->update($essay, $validatedData);

        return response()->json(
            new EssayResource($essayUpdated),
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Domain\Examables\Essay\Models\Essay  $essay
     * @return \Illuminate\Http\Response
     */
    public function destroy(Essay $essay)
    {
        $this->authorize('delete', $essay);

        $this->essayService->delete($essay);

        return response()->json(
            status: Response::HTTP_NO_CONTENT
        );
    }
}
