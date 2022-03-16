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

    //TO DO 
    public function create(array $dataToCreate): Model
    {
        $dataToCreateCollection = collect($dataToCreate);
        //CREATE A EXAM
        $createExamAction = new Create(new Exam());

        $examCreated = $createExamAction($this->filterDataToCreateExam($dataToCreateCollection));

        $dataToCreateCollection->put('exam_id', $examCreated->id);

        //CREATE A TEST

        $createTestAction = new Create(new Test());

        $testCreated = $createTestAction($this->filterDataToCreateTest($dataToCreateCollection));

        $dataToCreateCollection->put(
            'test_id',
            $testCreated->id
        );

        //STORE TOPIC'S TEST

        if ($dataToCreateCollection->has('topics')) {

            $topicsToBeCreated = $this->filterDataToCreateTopic($dataToCreateCollection);
            $createTopicAction = new Create(new Topic());

            $topicsToBeCreated->each(function ($item) use ($createTopicAction, $dataToCreateCollection) {
                $item['test_id'] = $dataToCreateCollection['test_id'];

                $createTopicAction($item);
            });
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
}
