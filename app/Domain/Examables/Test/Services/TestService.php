<?php


namespace Domain\Examables\Test\Services;

use App\Support\Actions\CrudModelOperations\Create;
use Domain\Exam\Models\Exam;
use Domain\Examables\Test\Topic\Models\Topic;
use App\Support\Services\CrudModelOperationsService;
use App\Domain\Examables\Test\Models\Test;
use App\Support\Exceptions\CrudModelOperations\RegisterRecordFailException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class TestService extends CrudModelOperationsService
{

    public function __construct(private Test $test)
    {
        parent::__construct($test);
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

        $getAll  = Test::ofUser($user)->latest()->get();

        return $getAll;
    }

    public function create(array $dataToCreate): Model
    {
        $dataToCreateCollection = collect($dataToCreate);

        $this->test = $this->storeTest($dataToCreateCollection);

        throw_if(is_null($this->test), RegisterRecordFailException::class);

        $this->storeExam($dataToCreateCollection);

        $hasTestsTopicToStore = ($dataToCreateCollection->has('topics'));

        if ($hasTestsTopicToStore) {
            $this->storeTopicTest($dataToCreateCollection);
        }

        return $this->test;
    }

    public function update(Model $model, array $dataToUpdate): Model
    {
        $updateAction  = $this->updateAction;

        ($updateAction($model->exam, $dataToUpdate));

        return $model;
    }

    public function addNewTopic(Test $test, array $dataToCreateNewTopic)
    {
        $createTestAction = new Create(new Topic());

        $dataToCreateNewTopic['test_id'] = $test->id;

        $createTestAction(
            $dataToCreateNewTopic
        );

        return $test;
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
        $name = $request->name;

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
            ->when($name, function ($query, $name) {
                return  $query->whereHas(
                    'topics',
                    function ($query) use ($name) {

                        $lowerName = strtolower($name);
                        return $query->whereRaw('LOWER(name) LIKE ?', ["%$lowerName%"]);
                    }
                );
            });
        $collection = $query->get();

        return $collection;
    }

    private function filterDataToCreateExam(Collection $dataToCreate): Collection
    {
        $filter = ['subject_id', 'effective_date'];

        $collectionDataToCreate = $dataToCreate->only($filter);

        $collectionDataToCreate->put('examable_id', $this->test->id);

        $collectionDataToCreate->put('examable_type', $this->test::class);


        return $collectionDataToCreate;
    }


    private function filterDataToCreateTopic(Collection $dataToCreate): Collection
    {
        $filter = ['topics'];

        $collectionDataToCreate = $dataToCreate->only($filter)->flatten(1);

        return $collectionDataToCreate;
    }

    private function storeExam($dataToCreateExam): void
    {
        $createExamAction = new Create(new Exam());

        $dataToCreateExamFiltered = $this->filterDataToCreateExam($dataToCreateExam);

        $createExamAction($dataToCreateExamFiltered->toArray());
    }

    private function storeTest(): Test
    {
        $createTestAction = new Create(new Test());

        $testCreated = $createTestAction([]);

        return $testCreated;
    }

    private function storeTopicTest($dataToCreate): void
    {
        $topicsToBeCreated = $this->filterDataToCreateTopic($dataToCreate);

        if (($topicsToBeCreated->count()) <= 0) {
            return;
        }

        $createTopicAction = new Create(new Topic());

        $topicsToBeCreated->each(function ($item) use ($createTopicAction) {
            $item['test_id'] = $this->test->id;

            $createTopicAction($item);
        });
    }
}
