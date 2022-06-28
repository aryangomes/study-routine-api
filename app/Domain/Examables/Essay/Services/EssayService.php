<?php

declare(strict_types=1);

namespace App\Domain\Examables\Essay\Services;

use App\Domain\Examables\Essay\Models\Essay;
use App\Support\Exceptions\CrudModelOperations\RegisterRecordFailException;
use App\Support\Services\CrudModelOperationsService;
use Domain\Exam\Models\Exam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
        $dataToCreateCollection = collect($dataToCreate);

        $this->essay = $this->createEssay($dataToCreateCollection);

        throw_if(is_null($this->essay), RegisterRecordFailException::class);

        $this->createExam($dataToCreateCollection);

        return $this->essay;
    }

    private function createEssay(Collection $dataToCreateEssay): Essay
    {
        $dataToCreateEssay = $this->filterDataToCreateEssay($dataToCreateEssay);

        $essayCreated = Essay::create($dataToCreateEssay->toArray());

        return $essayCreated;
    }

    private function createExam(Collection $dataToCreateExam): void
    {
        $dataToCreateExam = $this->filterDataToCreateExam($dataToCreateExam);

        Exam::create($dataToCreateExam->toArray());
    }
    private function filterDataToCreateEssay(Collection $dataToFilter): Collection
    {
        $filter = [
            'topic',
            'observation'
        ];
        return $dataToFilter->only($filter);
    }


    private function filterDataToCreateExam(Collection $dataToFilter): Collection
    {
        $filter = [

            'effective_date',
            'subject_id',
            'examable_id',
            'examable_type',
        ];

        $dataToFilter->put('examable_id', $this->essay->id);
        $dataToFilter->put('examable_type', $this->essay::class);


        return $dataToFilter->only($filter);
    }
}
