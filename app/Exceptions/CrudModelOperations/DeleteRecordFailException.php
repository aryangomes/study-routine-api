<?php

namespace App\Exceptions\CrudModelOperations;

use Exception;
use Illuminate\Database\Eloquent\Model;

class DeleteRecordFailException extends Exception
{
    public function __construct(Model $model)
    {
        $modelClassName = ((new \ReflectionClass($model))->getShortName());
        $this->message = __('crud_model_operations.delete.failed', [
            'model' => $modelClassName
        ]);
    }
}
