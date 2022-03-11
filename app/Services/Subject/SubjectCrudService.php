<?php


namespace App\Services\Subject;

use App\Actions\Subject\CreateSubject;
use App\Models\Subject;

class SubjectCrudService
{

    public function __construct(
        private Subject $subject
    ) {
    }
    /**
     * Create a subject
     * @param array $dataToCreateSubject
     * @return Subject
     */
    public function createSubject(array $dataToCreateSubject): Subject
    {
        $createSubjectAction = new CreateSubject();

        $subjectCreated = $createSubjectAction($dataToCreateSubject);

        return $subjectCreated;
    }
}
