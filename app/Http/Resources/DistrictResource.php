<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DistrictResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $this->upazila->load('postOffice');
       // $this->upazila->load('postOffice');
        return [
            'id'   =>$this->id,
            'name' =>$this->name,
            'upazilas'=> UpazilaResource::collection($this->whenLoaded('upazila')),
        ];
    }
}
