<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
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
            'id'                => $this->id,
            'type'              =>$this->type == 1?'Public':'Private',
            'company_name'      =>$this->company_name,
            'location'          =>$this->location,
            'designation'       =>$this->designation,
            'salary'            =>$this->salary,
            'currently_working' =>$this->currently_working==1?'Currently Working':'Currently Not working',
            'start_date'        =>$this->start_date,
            'end_date'          =>!$this->end_date?'N\A':$this->end_date    
        ];
    }
}
