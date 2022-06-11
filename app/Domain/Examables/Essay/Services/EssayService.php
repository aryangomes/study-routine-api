<?php

declare(strict_types=1);

namespace App\Domain\Examables\Essay\Services;

use App\Domain\Examables\Essay\Models\Essay;
use App\Support\Services\CrudModelOperationsService;
use Domain\Exam\Models\Exam;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class EssayService extends CrudModelOperationsService
{
    private Essay $essay;

    public function __construct()
    {
        $this->essay = new Essay();
        parent::__construct($this->essay);
    }

    /**
     * Get all records in the database
     *
     * 
     * @return Collection
     **/
    public function getAll(): Collection
    {
        $authenticatedUser = auth()->user();

        $getAll  = $this->essay::ofUser($authenticatedUser)->latest()->get();

        return $getAll;
    }

    /**
     * Create and store a record in database
     *
     * @param array $dataToCreate
     * @return Model
     **/
    public function create(array $dataToCreate): Model
    {
        $this->createEssay($this->filterDataToCreateEssay($dataToCreate));

        $this->createExam($this->filterDataToCreateExam($dataToCreate));

        return $this->essay;
    }

    private function createEssay(array $dataToCreateEssay): void
    {
        $this->essay = Essay::create($dataToCreateEssay);
    }

    private function createExam(array $dataToCreateExam): void
    {
        Exam::create($dataToCreateExam);
    }

    private function filterDataToCreate(array $dataToFilter, array $filter): array
    {
        return Arr::only($dataToFilter, $filter);
    }

    private function filterDataToCreateEssay(array $dataToFilter): array
    {
        $filter = [
            'topic',
            'observation'
        ];
        return $this->filterDataToCreate($dataToFilter, $filter);
    }


    private function filterDataToCreateExam(array $dataToFilter): array
    {
        $filter = [

            'effective_date',
            'subject_id',
            'examable_id',
            'examable_type',
        ];

        $dataToFilter = Arr::add($dataToFilter, 'examable_id', $this->essay->id);
        $dataToFilter = Arr::add($dataToFilter, 'examable_type', $this->essay::class);


        return $this->filterDataToCreate($dataToFilter, $filter);
    }
}
