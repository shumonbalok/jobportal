<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserNotificationResource extends JsonResource
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
            'notify' => (int)$this->notify_type,
            'notify_fav_type' => (int)$this->notify_fav_type,
            'notify_hired_type' => (int)$this->notify_hired_type,
            'notify_unemployed_type' => (int)$this->notify_unemployed_type,
        ];
    }
}
