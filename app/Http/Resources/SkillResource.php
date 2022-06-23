<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SkillResource extends JsonResource
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
            'id'                =>$this->id,
            'name'              =>$this->name,
            'institute_name'    =>$this->institute_name,
            'duration'          =>$this->duration,
            'result'            =>$this->result
        ];
    }
}
