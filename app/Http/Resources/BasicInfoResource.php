<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BasicInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $this->load('quota');
        return [
            'id'            =>$this->id,
            'full_name'     =>$this->full_name,
            'father_name'   =>$this->father_name,
            'mother_name'   =>$this->mother_name,
            'birth_date'    =>$this->birth_date,
            'gender'        =>$this->gender,
            'height'        =>$this->height,
            'religion'      =>$this->religion,
            'marital_status'=>$this->marital_status,
            'nid'           =>$this->nid,
            'mobile'        =>$this->mobile,
            'email'         =>$this->email,
            'passport_number'         =>$this->passport_number,
            'quota'         =>QuotaResource::make($this->quota)
        ];
    }
}
