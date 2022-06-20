<?php

declare(strict_types=1);

namespace App\Domain\DailyActivity\Services;

use App\Domain\DailyActivity\Models\DailyActivity;
use App\Support\Services\CrudModelOperationsService;
use Illuminate\Database\Eloquent\Collection;

class DailyActivityService extends CrudModelOperationsService
{
    public function __construct()
    {
        parent::__construct(new DailyActivity());
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


        $collection = $this->model::ofUser($user)->today()->get();

        return $collection;
    }
}
