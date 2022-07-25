<?php

declare(strict_types=1);

namespace App\Domain\DailyActivity\Services;

use App\Domain\DailyActivity\Models\DailyActivity;
use App\Support\Actions\CrudModelOperations\Create;
use App\Support\Services\CrudModelOperationsService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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

    private function getActivitableTypeClass(string $activitableType): string
    {
        $activitableTypeClass = DailyActivity::getActivitables()[$activitableType];

        return $activitableTypeClass;
    }
}
