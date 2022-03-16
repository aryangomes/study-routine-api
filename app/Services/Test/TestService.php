<?php


namespace App\Services\Test;

use App\Actions\CrudModelOperations\Create;
use App\Models\Exam;
use App\Models\Test;
use App\Models\Topic;
use App\Services\CrudModelOperationsService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TestService extends CrudModelOperationsService
{

    public function __construct(private Test $test)
    {
    }

    public function create(array $dataToCreate): Model
    {
        $dataToCreateCollection = collect($dataToCreate);

        $examCreated = $this->storeExam($dataToCreateCollection);

        $dataToCreateCollection->put('exam_id', $examCreated->id);

        $testCreated = $this->storeTest($dataToCreateCollection);

        $dataToCreateCollection->put('test_id', $testCreated->id);

        $hasTestsTopicToStore = ($dataToCreateCollection->has('topics') &&
            ($dataToCreateCollection->count() > 0));

        if ($hasTestsTopicToStore) {
            $this->storeTopicTest($dataToCreateCollection);
        }

        return $testCreated;
    }

    private function filterDataToCreateExam(Collection $dataToCreate): array
    {
        $collectionDataToCreate = $dataToCreate->only(
            ['subject_id', 'effective_date']
        );

        $dataToCreateExam = $collectionDataToCreate->toArray();

        return $dataToCreateExam;
    }

    private function filterDataToCreateTest(Collection $dataToCreate): array
    {
        $collectionDataToCreate = $dataToCreate->only(['name', 'exam_id']);

        $dataToCreateTest = $collectionDataToCreate->toArray();

        return $dataToCreateTest;
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

    private function storeTest($dataToCreate): Test
    {
        $createTestAction = new Create(new Test());

        $testCreated = $createTestAction($this->filterDataToCreateTest($dataToCreate));

        return $testCreated;
    }

    private function storeTopicTest($dataToCreate): void
    {
        $topicsToBeCreated = $this->filterDataToCreateTopic($dataToCreate);

        $createTopicAction = new Create(new Topic());

        $topicsToBeCreated->each(function ($item) use ($createTopicAction, $dataToCreate) {
            $item['test_id'] = $dataToCreate['test_id'];

            $createTopicAction($item);
        });
    }
}
