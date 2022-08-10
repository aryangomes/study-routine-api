<?php


namespace Domain\Subject\Services;

use Domain\Subject\Models\Subject;
use App\Support\Services\CrudModelOperationsService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SubjectService extends CrudModelOperationsService
{
    public function __construct(private Subject $subject)
    {
        parent::__construct($subject);
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

        $collection = $user->subjects;

        return $collection;
    }

    /**
     * Create and store a record in database
     *
     * @param array $dataToCreate
     * @return Subject
     **/
    public function create(array $dataToCreate): Subject
    {
        $createAction = $this->createAction;

        $dataToCreate = array_merge(
            $dataToCreate,
            [
                'user_id' => auth()->id()
            ]
        );

        $subjectCreated = $createAction($dataToCreate);

        return $subjectCreated;
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

        $name = $request->name;


        $query = $this->model::query()
            ->where('user_id', $user->id)
            ->when($name, function ($query, $name) {
                $lowerName = strtolower($name);
                return $query->whereRaw('LOWER(name) LIKE ?', ["%$lowerName%"]);
            });

        $collection = $query->get();

        return $collection;
    }
}
