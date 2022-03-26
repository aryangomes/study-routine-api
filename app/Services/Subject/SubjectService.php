<?php


namespace App\Services\Subject;

use App\Models\Subject;
use App\Services\CrudModelOperationsService;
use Illuminate\Database\Eloquent\Collection;

class SubjectService extends CrudModelOperationsService
{
    public function __construct(private Subject $subject)
    {
        parent::__construct($subject);
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

        $collection = $user->subjects;

        return $collection;
    }

    /**
     * Create and store a record in database
     *
     * @param array $dataToCreate
     * @return Subject
     **/
    public function create(array $dataToCreate): Subject
    {
        $createAction = $this->createAction;

        $dataToCreate = array_merge(
            $dataToCreate,
            [
                'user_id' => auth()->id()
            ]
        );

        $subjectCreated = $createAction($dataToCreate);

        return $subjectCreated;
    }
}
