<?php

namespace App\Support\Exceptions\CrudModelOperations;

use Exception;
use Illuminate\Database\Eloquent\Model;

class GetAllRecordsFailException extends Exception
{
    public function __construct(Model $model)
    {
        $modelClassName = ((new \ReflectionClass($model))->getShortName());
        $this->message = __('crud_model_operations.get_all_failed', [
            'model' => $modelClassName
        ]);
    }
}
