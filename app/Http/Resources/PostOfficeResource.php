<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostOfficeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $this->load('upazila');
        $this->upazila->load('district');
        return [
            'id'            =>$this->id,
            'Upazila_id'    =>$this->upazila_id,
            'District_id'   =>$this->upazila->district_id,
            'name'          =>$this->name
        ];
    }
}
