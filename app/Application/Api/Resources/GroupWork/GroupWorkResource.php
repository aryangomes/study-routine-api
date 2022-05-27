<?php

namespace App\Application\Api\Resources\GroupWork;

use App\Application\Api\Resources\Exam\ExamResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupWorkResource extends JsonResource
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
            "id" => $this->id,
            'exam' => new ExamResource($this->exam),
            "topic" => $this->topic,
            "note" => $this->note,

        ];
    }
}
