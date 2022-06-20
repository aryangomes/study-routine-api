<?php


namespace Domain\Examables\Test\Services;

use App\Support\Actions\CrudModelOperations\Create;
use Domain\Exam\Models\Exam;
use Domain\Examables\Test\Topic\Models\Topic;
use App\Support\Services\CrudModelOperationsService;
use App\Domain\Examables\Test\Models\Test;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ExamTestService extends CrudModelOperationsService
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

        $getAll  = Test::ofUser($user)->get();

        return $getAll;
    }

    public function create(array $dataToCreate): Model
    {
        $dataToCreateCollection = collect($dataToCreate);

        $this->test = $this->storeTest($dataToCreateCollection);

        $this->storeExam($dataToCreateCollection);

        $hasTestsTopicToStore = ($dataToCreateCollection->has('topics') &&
            ($dataToCreateCollection->count() > 0));

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

    private function filterDataToCreateExam(Collection $dataToCreate): array
    {

        $collectionDataToCreate = $dataToCreate->only(
            ['subject_id', 'effective_date']
        );

        $collectionDataToCreate->put('examable_id', $this->test->id);

        $collectionDataToCreate->put('examable_type', $this->test::class);


        $dataToCreateExam = $collectionDataToCreate->toArray();

        return $dataToCreateExam;
    }


    private function filterDataToCreateTopic(Collection $dataToCreate): Collection
    {
        $collectionDataToCreate = $dataToCreate->only('topics')->flatten(1);

        return $collectionDataToCreate;
    }

    private function storeExam($dataToCreate): Exam
    {
        $createExamAction = new Create(new Exam());

        $examCreated = $createExamAction($this->filterDataToCreateExam($dataToCreate));

        return $examCreated;
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

        $createTopicAction = new Create(new Topic());

        $topicsToBeCreated->each(function ($item) use ($createTopicAction, $dataToCreate) {
            $item['test_id'] = $this->test->id;

            $createTopicAction($item);
        });
    }
}
