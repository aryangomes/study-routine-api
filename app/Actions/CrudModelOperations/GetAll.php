<?php

namespace App\Actions\CrudModelOperations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GetAll
{
    public function __construct(private Model $model)
    {
    }

    public function __invoke(): Collection
    {
        $collectionModel = [];
        try {
            DB::beginTransaction();
            $collectionModel = $this->model->all();
        } catch (\Exception $exception) {
        }

        return $collectionModel;
    }
}
