<?php


namespace App\Domain\Examables\GroupWork\Services;

use App\Domain\Exam\Actions\CreateExam;
use App\Domain\Examables\GroupWork\Member\Actions\AddMemberToGroupWork;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use App\Support\Actions\CrudModelOperations\Create;
use App\Support\Services\CrudModelOperationsService;
use Domain\Exam\Models\Exam;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

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
        $authenticatedUser = auth()->user();

        $getAll  = GroupWork::ofUser($authenticatedUser)->orderBy('created_at', 'desc')->get();

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

        $dataToCreateGroupWork = Arr::only($dataToCreate, ['topic', 'note']);

        $createGroupWork = new Create(new GroupWork());
        $this->groupWork = $createGroupWork($dataToCreateGroupWork);

        $dataToCreateExam = Arr::only($dataToCreate, ['effective_date', 'subject_id']);
        $dataToCreateExam = Arr::Add($dataToCreateExam, 'examable_type', GroupWork::class);
        $dataToCreateExam = Arr::Add($dataToCreateExam, 'examable_id', $this->groupWork->id);

        $createExam = new Create(new Exam());
        $exam = $createExam($dataToCreateExam);

        $addDefaultMemberGroupWork = new AddMemberToGroupWork($this->groupWork);

        $authenticatedUser = auth()->user();

        $addDefaultMemberGroupWork($authenticatedUser->getAuthIdentifier());

        return $this->groupWork;
    }

    public function update(Model $groupWork, array $dataToUpdate): Model
    {
        $updateAction  = $this->updateAction;

        $updateAction($groupWork, $dataToUpdate);

        $updateAction($groupWork->exam, $dataToUpdate);

        return $groupWork;
    }
}
