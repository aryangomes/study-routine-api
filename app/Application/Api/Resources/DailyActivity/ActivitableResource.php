<?php

namespace App\Application\Api\Resources\DailyActivity;

use App\Application\Api\Resources\Exam\ExamResource;
use App\Application\Api\Resources\Homework\HomeworkResource;
use App\Domain\Homework\Models\Homework;
use Domain\Exam\Models\Exam;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivitableResource extends JsonResource
{

    public function __construct(
        $resource,
        private string $activitableType,
        private int $activitableId
    ) {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        if (Exam::class == $this->activitableType) {

            return new ExamResource(Exam::find($this->activitableId));
        }

        return new HomeworkResource(Homework::find($this->activitableId));
    }
}
