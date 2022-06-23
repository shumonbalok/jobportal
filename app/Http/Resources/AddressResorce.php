<?php

namespace App\Http\Resources;

use App\Models\Address;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResorce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
      //  dd($this->district);
        return [
            'care_of' =>$this->care_of,
            'details' =>$this->details,
            'district'=> DistrictResource::make($this->whenLoaded('district')),
            'upazila' => UpazilaResource::make($this->whenLoaded('upazila')),
            'post_office' => PostOfficeResource::make($this->whenLoaded('postOffice')),
            'postal_code' => $this->postal_code,
            'type'       => (int)$this->type,
        ];
    }
}
