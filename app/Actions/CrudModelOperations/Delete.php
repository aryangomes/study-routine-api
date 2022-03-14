<?php

namespace App\Actions\CrudModelOperations;

use App\Exceptions\CrudModelOperations\DeleteRecordFailException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Delete


{
    /**
     *
     * @param Model $model
     * @return void
     */
    public function __invoke(Model $model)
    {

        try {

            DB::beginTransaction();

            $modelWasDelete = $model->delete();
        } catch (DeleteRecordFailException $exception) {
        }

        $modelWasDelete ? DB::commit() : DB::rollBack();
    }
}
