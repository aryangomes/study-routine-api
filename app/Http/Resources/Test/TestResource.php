<?php

namespace App\Http\Resources\Test;

use App\Http\Resources\Exam\ExamResource;
use App\Http\Resources\Topic\TopicResource;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'exam' => new ExamResource($this->exam),
            'topics' => TopicResource::collection($this->topics),
        ];
    }
}
