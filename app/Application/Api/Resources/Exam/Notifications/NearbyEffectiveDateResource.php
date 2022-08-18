<?php

namespace App\Application\Api\Resources\Exam\Notifications;

use Illuminate\Http\Resources\Json\JsonResource;

class NearbyEffectiveDateResource extends JsonResource
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
            "exam" => $this->data,
            "read_at" => $this->read_at,

        ];
    }
}
