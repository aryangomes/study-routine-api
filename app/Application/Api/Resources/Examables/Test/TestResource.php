<?php

namespace App\Application\Api\Resources\Examables\Test;

use App\Application\Api\Resources\Examables\Test\Topic\TopicResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Application\Api\Resources\Exam\ExamableResource;

class TestResource extends JsonResource
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
            'topics' => TopicResource::collection($this->topics),
            'exam' => new ExamableResource($this->exam),
        ];
    }
}
