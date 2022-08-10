<?php

declare(strict_types=1);

namespace App\Domain\Homework\Services;

use App\Domain\Homework\Models\Homework;
use App\Support\Services\CrudModelOperationsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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

    /**
     * Get filtered records by query parameters in the database
     *
     * 
     * @return Collection
     **/
    public function getRecordsFilteredByQuery(Request $request): Collection
    {

        $user = auth()->user();


        $subjectId = $request->subject_id;
        $dueDate = $request->due_date;
        $startDueDate = $request->start_due_date;
        $endDueDate = $request->end_due_date;
        $title = $request->title;


        $query = $this->model::query()
            ->ofUser($user)
            ->when($subjectId, function ($query, $subjectId) {
                return $query->where('subject_id', $subjectId);
            })
            ->when($title, function ($query, $title) {
                $lowerTitle = strtolower($title);
                return $query->whereRaw('LOWER(title) LIKE ?', ["%$lowerTitle%"]);
            })
            ->when($dueDate, function ($query, $dueDate) {

                return $query->whereDate('due_date', $dueDate);
            })
            ->when(($startDueDate && $endDueDate), function ($query) use ($startDueDate, $endDueDate) {
                return $query->whereBetween('due_date', [$startDueDate, $endDueDate]);
            });

        $collection = $query->get();

        return $collection;
    }
}
