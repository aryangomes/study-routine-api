<?php


namespace App\Services\Subject;

use App\Models\User;
use App\Services\CrudModelOperationsService;
use Illuminate\Database\Eloquent\Collection;

class SubjectService extends CrudModelOperationsService
{
    /**
     * Get all records in the database
     *
     * 
     * @return Collection
     **/
    public function getAll(): Collection
    {
        $user = auth()->user();

        $collection = $user->subjects;

        return $collection;
    }
}
