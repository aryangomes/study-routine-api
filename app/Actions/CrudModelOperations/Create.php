<?php


namespace App\Actions\CrudModelOperations;

use App\Exceptions\CrudModelOperations\RegisterRecordFailException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Create
{

    public function __construct(private Model $model)
    {
    }

    /**
     *
     * @param string $modelClass
     * @param array $dataToCreate
     * @return Model
     */
    public function __invoke(array $dataToCreate): Model
    {
        try {
            DB::beginTransaction();

            $modelCreated = $this->model::create($dataToCreate);

            DB::commit();
        } catch (RegisterRecordFailException $exception) {
            DB::rollBack();
        }

        return $modelCreated;
    }
}
