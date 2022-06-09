<?php

declare(strict_types=1);

namespace App\Domain\Homework\Services;

use App\Domain\Homework\Models\Homework;
use App\Support\Services\CrudModelOperationsService;
use Illuminate\Database\Eloquent\Collection;

class HomeworkService extends CrudModelOperationsService
{
    public function __construct()
    {
        parent::__construct(new Homework());
    }

    /**
     * Get all records in the database
     *
     * 
     * @return Collection
     **/
    public function getAll(): Collection
    {
        $user = auth()->user();

        $collection = $this->model::ofUser($user)->get();

        return $collection;
    }
}
