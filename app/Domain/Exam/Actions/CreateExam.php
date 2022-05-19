<?php


namespace App\Domain\Exam\Actions;

use App\Support\Actions\CrudModelOperations\Create;
use Domain\Exam\Models\Exam;

class CreateExam extends Create
{
    public function __construct()
    {
        parent::__construct(new Exam());
    }
}
