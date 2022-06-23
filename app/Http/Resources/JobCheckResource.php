<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobCheckResource extends JsonResource
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
        'min_age_check' => (int)$this->min_age_check,
        'max_age_check' => (int)$this->max_age_check,
        'distric_check' => (int)$this->distric_check,
        'quota_check' => (int)$this->quota_check,
        'skill_check' => (int)$this->skill_check,
        'experience_check' =>(int)$this->experience_check
        ];
    }
}
