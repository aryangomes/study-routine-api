<?php

declare(strict_types=1);

namespace App\Actions\Exam;

use App\Actions\CrudModelOperations\Create;
use App\Models\Exam;

class CreateExam extends Create
{

    public function __construct()
    {
        parent::__construct(new Exam);
    }

    public function __invoke(array $dataToCreate): Exam
    {
        $createAction = new Create(new Exam);
        $examCreated =
            $createAction($dataToCreate);

        return $examCreated;
    }
}
