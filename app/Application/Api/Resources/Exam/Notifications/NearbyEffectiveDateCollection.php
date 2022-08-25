<?php

namespace App\Application\Api\Resources\Exam\Notifications;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NearbyEffectiveDateCollection extends ResourceCollection
{

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = NearbyEffectiveDateResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
