<?php

namespace App\Application\Api\Resources\Authentication;

use Illuminate\Http\Resources\Json\JsonResource;

class UserLoggedResource extends JsonResource
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
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'user_avatar_path' => $this->user_avatar_path,
            'accessToken' => $this->currentAccessToken(),

        ];
    }
}
