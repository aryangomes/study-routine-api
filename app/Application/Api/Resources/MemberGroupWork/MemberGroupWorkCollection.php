<?php

namespace App\Application\Api\Resources\MemberGroupWork;

use Illuminate\Http\Resources\Json\ResourceCollection;

class MemberGroupWorkCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = MemberGroupWorkResource::class;
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
