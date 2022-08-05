<?php

declare(strict_types=1);

namespace App\Domain\DailyActivity\Services;

use App\Domain\DailyActivity\Models\DailyActivity;
use App\Support\Actions\CrudModelOperations\Create;
use App\Support\Services\CrudModelOperationsService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class DailyActivityService extends CrudModelOperationsService
{
    public function __construct()
    {
        parent::__construct(new DailyActivity());
    }

    /**
     * Get all records in the database
     *
     * 
     * @return Collection
     **/
    public function getAll(): Collection
    {
        $user = auth()->user();

        $collection = $this->model::ofUser($user)->today()->latest()->get();

        return $collection;
    }

    /**
     * Get filtered records by query parameters in the database
     *
     * 
     * @return Collection
     **/
    public function getRecordsFilteredByQuery(Request $request): Collection
    {

        $user = auth()->user();


        $subjectId = $request->subject_id;
        $dateOfActivity = $request->date_of_activity;
        $startTime = $request->start_time;
        $endTime = $request->end_time;
        $activitableType = $request->activitable_type;

        if (isset($activitableType)) {

            $activitableType = $this->getActivitableTypeClass($activitableType);
        }

        $query = $this->model::query()
            ->ofUser($user)
            ->when($dateOfActivity, function ($query, $dateOfActivity) {
                return $query->whereDate('date_of_activity', $dateOfActivity);
            })
            ->when($startTime, function ($query, $startTime) {
                return $query->where('start_time', $startTime);
            })

            ->when($endTime, function ($query, $endTime) {
                return $query->where('end_time', $endTime);
            })

            ->when(
                $activitableType,
                function ($query, $activitableType) {

                    return $query->where('activitable_type', $activitableType);
                }
            )
            ->when($subjectId, function ($query, $subjectId) {
                return  $query->whereHas(
                    'activitable',
                    function ($query) use ($subjectId) {

                        $query->with('activitable.subject')->where('subject_id', $subjectId);
                    }
                );
            });

        $collection = $query->get();

        return $collection;
    }

    public function create(array $dataToCreate): Model
    {
        $dataToCreateCollection = collect($dataToCreate);

        $dataToCreateCollection =
            $this->setActivitableTypeClassToDataToCreateDailyActivity(
                $dataToCreateCollection
            );

        $createAction = new Create(new DailyActivity());

        $modelCreated = $createAction($dataToCreateCollection->toArray());

        return $modelCreated;
    }

    private function setActivitableTypeClassToDataToCreateDailyActivity($dataToCreateCollection): Collection
    {
        $activitableType = $dataToCreateCollection->get('activitable_type');

        $dataToCreateCollection =  $dataToCreateCollection->replace([
            'activitable_type' => $this->getActivitableTypeClass($activitableType)
        ]);

        return $dataToCreateCollection;
    }

    private function getActivitableTypeClass(string $activitableType): ?string
    {
        $activitableTypeClass = null;

        $activitablesType = DailyActivity::getActivitables();

        if (key_exists($activitableType, $activitablesType)) {

            $activitableTypeClass = $activitablesType[$activitableType];
        }

        return $activitableTypeClass;
    }
}
