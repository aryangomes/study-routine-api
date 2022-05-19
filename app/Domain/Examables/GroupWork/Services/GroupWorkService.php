<?php


namespace App\Domain\Examables\GroupWork\Services;

use App\Domain\Exam\Actions\CreateExam;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use App\Support\Services\CrudModelOperationsService;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class GroupWorkService extends CrudModelOperationsService
{
    public function __construct(private GroupWork $groupWork)
    {
        parent::__construct($groupWork);
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

        $getAll  = GroupWork::ofUser($user)->orderBy('created_at', 'desc')->get();

        return $getAll;
    }

    /*
    * Create and store a record in database
    *
    * @param array $dataToCreate
    * @return Model
    **/
    public function create(array $dataToCreate): GroupWork
    {
        $dataToCreateExam = Arr::only($dataToCreate, ['subject_id', 'effective_date']);
        $dataToCreate = Arr::except($dataToCreate, ['subject_id', 'effective_date']);

        $createAction = $this->createAction;

        $this->groupWork = $createAction($dataToCreate);


        $dataToCreateExam = Arr::add($dataToCreateExam, 'examable_id', $this->groupWork->id);
        $dataToCreateExam = Arr::add($dataToCreateExam, 'examable_type', $this->groupWork::class);

        $createExamAction = new CreateExam();
        $exam = $createExamAction($dataToCreateExam);



        return $this->groupWork;
    }
}
