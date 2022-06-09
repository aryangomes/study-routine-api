<?php

namespace App\Application\Api\Resources\Homework;

use App\Application\Api\Resources\Subject\SubjectResource;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeworkResource extends JsonResource
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
            'title' => $this->title,
            'observation' => $this->observation,
            'due_date' => $this->due_date,
            'subject' => new SubjectResource($this->subject),
        ];
    }
}
