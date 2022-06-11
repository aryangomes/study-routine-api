<?php

namespace App\Application\Api\Resources\Exam;

use App\Application\Api\Resources\Subject\SubjectResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'effective_date' => $this->effective_date,
            'subject' => new SubjectResource($this->subject),

        ];
    }
}
