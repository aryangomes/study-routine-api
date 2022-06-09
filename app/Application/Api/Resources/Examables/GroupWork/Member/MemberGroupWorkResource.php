<?php

namespace App\Application\Api\Resources\Examables\GroupWork\Member;

use App\Application\Api\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberGroupWorkResource extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return
            [
                'id' => $this->id,
                'user' => new UserResource($this->user),
            ];
    }
}
