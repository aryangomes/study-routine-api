<?php


namespace App\Domain\Examables\GroupWork\Services;

use App\Domain\Exam\Actions\CreateExam;
use App\Domain\Examables\GroupWork\Member\Actions\AddMemberToGroupWork;
use App\Domain\Examables\GroupWork\Models\GroupWork;
use App\Support\Actions\CrudModelOperations\Create;
use App\Support\Exceptions\CrudModelOperations\RegisterRecordFailException;
use App\Support\Services\CrudModelOperationsService;
use Domain\Exam\Models\Exam;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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

        $getAll  = GroupWork::ofUser($authenticatedUser)->latest()->get();

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
        $dataToCreateCollection = collect($dataToCreate);

        $this->groupWork = $this->storeGroupWork($dataToCreateCollection);

        throw_if(is_null($this->groupWork), RegisterRecordFailException::class);

        $this->storeExam($dataToCreateCollection);

        $this->addDefaultMemberToGroupWork();

        $this->addMembersToGroupWork($dataToCreateCollection);

        return $this->groupWork;
    }

    public function update(Model $groupWork, array $dataToUpdate): Model
    {
        $updateAction  = $this->updateAction;

        $updateAction($groupWork, $dataToUpdate);

        $updateAction($groupWork->exam, $dataToUpdate);

        return $groupWork;
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
        $effectiveDate = $request->effective_date;
        $topic = $request->topic;
        $note = $request->note;

        $query = $this->model::query()
            ->ofUser($user)
            ->when($subjectId, function ($query, $subjectId) {
                return  $query->whereHas(
                    'exam',
                    function ($query) use ($subjectId) {

                        $query->with('exam.subject')->where('subject_id', $subjectId);
                    }
                );
            })

            ->when($effectiveDate, function ($query, $effectiveDate) {
                return  $query->whereHas(
                    'exam',
                    function ($query) use ($effectiveDate) {

                        $query->whereDate('effective_date', $effectiveDate);
                    }
                );
            })
            ->when($topic, function ($query, $topic) {
                $lowerTopic = strtolower($topic);
                return $query->whereRaw('LOWER(topic) LIKE ?', ["%$lowerTopic%"]);
            })
            ->when($note, function ($query, $note) {
                $lowerNote = strtolower($note);
                return $query->whereRaw('LOWER(note) LIKE ?', ["%$lowerNote%"]);
            });

        $collection = $query->get();

        return $collection;
    }

    private function filterDataToCreateGroupWork(Collection $dataToCreate): Collection
    {
        $filter = ['topic', 'note'];

        $dataToCreateGroupWork = $dataToCreate->only($filter);

        return $dataToCreateGroupWork;
    }

    private function filterDataToCreateExam(Collection $dataToCreate): Collection
    {
        $filter = ['effective_date', 'subject_id'];

        $dataToCreateExam = $dataToCreate->only($filter);

        $dataToCreateExam = $dataToCreate->put('examable_type', GroupWork::class);

        $dataToCreateExam = $dataToCreate->put('examable_id', $this->groupWork->id);

        return $dataToCreateExam;
    }

    private function filterDataToAddMemberToGroupWork(Collection $dataToCreate): Collection
    {
        $filter = ['members'];

        $filterDataToAddMemberToGroupWork = $dataToCreate->only($filter);

        if (!$filterDataToAddMemberToGroupWork->has('members')) {
            return collect([]);
        }

        $dataToAddMembersToGroupWork = collect(
            $filterDataToAddMemberToGroupWork->get('members')
        );

        return $dataToAddMembersToGroupWork;
    }


    private function storeGroupWork(Collection $dataToCreateGroupWork): GroupWork
    {
        $dataToCreateGroupWorkFiltered = $this->filterDataToCreateGroupWork($dataToCreateGroupWork);

        $createGroupWork = new Create(new GroupWork());

        $workGroupCreated = $createGroupWork($dataToCreateGroupWorkFiltered->toArray());

        return $workGroupCreated;
    }

    private function storeExam(Collection $dataToCreateExam): void
    {
        $dataToCreateExamFiltered = $this->filterDataToCreateExam($dataToCreateExam);

        $createExam = new Create(new Exam());

        $createExam($dataToCreateExamFiltered->toArray());
    }

    private function addDefaultMemberToGroupWork(): void
    {
        $addDefaultMemberGroupWork = new AddMemberToGroupWork($this->groupWork);

        $authenticatedUser = auth()->user();

        $addDefaultMemberGroupWork($authenticatedUser->getAuthIdentifier());
    }

    private function addMembersToGroupWork(Collection $dataToCreate): void
    {

        $dataToAddMemberToGroupWork =
            $this->filterDataToAddMemberToGroupWork($dataToCreate);

        if (($dataToAddMemberToGroupWork->count()) <= 0) {
            return;
        }

        $addMemberToGroupWork = new AddMemberToGroupWork($this->groupWork);

        $dataToAddMemberToGroupWork->each(fn ($member) => $addMemberToGroupWork($member['user_id']));
    }
}
