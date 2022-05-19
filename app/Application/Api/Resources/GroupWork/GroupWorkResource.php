<?php

namespace App\Application\Api\Resources\GroupWork;

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
            "topic" => $this->topic,
            "note" => $this->note
        ];
    }
}
