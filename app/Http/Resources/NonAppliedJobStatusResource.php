<?php

namespace App\Http\Resources;

use App\Models\NonAppliedJob;
use Illuminate\Http\Resources\Json\JsonResource;

class NonAppliedJobStatusResource extends JsonResource
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

            'id'        =>$this->id,
            'status'    =>$this->status,
            'lat'       =>$this->lat,
            'long'      => $this->long,

        ];
    }
}
