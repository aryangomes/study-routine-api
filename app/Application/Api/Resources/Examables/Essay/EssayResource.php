<?php

namespace App\Application\Api\Resources\Examables\Essay;

use App\Application\Api\Resources\Exam\ExamableResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EssayResource extends JsonResource
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
            'topic' => $this->topic,
            'observation' => $this->observation,
            'exam' => new ExamableResource($this->exam)
        ];
    }
}
