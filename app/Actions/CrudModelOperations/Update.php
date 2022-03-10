<?php

namespace App\Actions\CrudModelOperations;

use App\Exceptions\CrudModelOperations\UpdateRecordFailException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Update
{

    /**
     * 
     * @param Model $model
     * @param array $dataToUpdate
     * @return void
     */
    public function __invoke(Model $model, array $dataToUpdate): void
    {
        try {
            DB::beginTransaction();
            $modelWasUpdate = $model->update($dataToUpdate);
        } catch (UpdateRecordFailException $exception) {
        }

        $modelWasUpdate ? DB::commit() : DB::rollBack();
    }
}
