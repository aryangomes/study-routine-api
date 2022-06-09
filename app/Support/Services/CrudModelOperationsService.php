<?php


namespace App\Support\Services;

use App\Support\Actions\CrudModelOperations\Create;
use App\Support\Actions\CrudModelOperations\Delete;
use App\Support\Actions\CrudModelOperations\GetAll;
use App\Support\Actions\CrudModelOperations\Update;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class CrudModelOperationsService
{

    protected GetAll $getAllAction;
    protected Create $createAction;
    protected Update $updateAction;
    protected Delete $deleteAction;
    public function __construct(protected Model $model)
    {
        $this->getAllAction = new GetAll($model);
        $this->createAction = new Create($model);
        $this->updateAction = new Update();
        $this->deleteAction = new Delete();
    }

    /**
     * Get all records in the database
     *
     * 
     * @return Collection
     **/
    public function getAll(): Collection
    {
        $getAll  = $this->getAllAction;

        $collection = $getAll();

        return $collection;
    }

    /**
     * Create and store a record in database
     *
     * @param array $dataToCreate
     * @return Model
     **/
    public function create(array $dataToCreate): Model
    {
        $createAction = $this->createAction;

        $modelCreated = $createAction($dataToCreate);

        return $modelCreated;
    }

    /**
     * Update a record in database
     * @param Model $model
     * @return Model
     **/
    public function update(Model $model, array $dataToUpdate): Model
    {
        $updateAction  = $this->updateAction;

        $updateAction($model, $dataToUpdate);

        return $model;
    }

    /**
     * Delete a record in database
     * @param Model $model
     * @return void
     **/
    public function delete(Model $model): void
    {
        $deleteAction  = $this->deleteAction;

        $deleteAction($model);
    }
}
