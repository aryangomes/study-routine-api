<?php

namespace App\Exceptions\CrudModelOperations;

use Exception;
use Illuminate\Database\Eloquent\Model;

class UpdateRecordFailException extends Exception
{
    public function __construct(Model $model)
    {
        $modelClassName = ((new \ReflectionClass($model))->getShortName());
        $this->message = __('crud_model_operations.update.failed', [
            'model' => $modelClassName
        ]);
    }
}
