<?php

namespace App\Application\Api\Resources\DailyActivity\Notifications;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDailyActivityResource extends JsonResource
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
            "activity" => $this->data,
            "read_at" => $this->read_at,

        ];
    }
}
