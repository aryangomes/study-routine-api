<?php

namespace App\Application\Api\Resources\DailyActivity;

use Illuminate\Http\Resources\Json\JsonResource;

class DailyActivityResource extends JsonResource
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = DailyActivityResource::class;

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
            'date_of_activity' => $this->date_of_activity->format('Y-m-d'),
            'start_time' => $this->start_time->format('H:i:s'),
            'end_time' => $this->end_time->format('H:i:s'),
            'activity' => new ActivitableResource(
                $this->activitable,
                $this->activitable_type,
                $this->activitable_id
            )
        ];
    }
}
