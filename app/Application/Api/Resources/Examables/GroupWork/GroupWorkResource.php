<?php

namespace App\Application\Api\Resources\Examables\GroupWork;

use App\Application\Api\Resources\Exam\ExamResource;
use App\Application\Api\Resources\Examables\GroupWork\Member\MemberGroupWorkCollection;
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
            "members" => new MemberGroupWorkCollection($this->members),

        ];
    }
}
