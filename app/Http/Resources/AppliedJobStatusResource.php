<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppliedJobStatusResource extends JsonResource
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
            'description' => $this->description,
            'short_name' => $this->short_name,
            'lat' => $this->lat ,
            'long'  =>$this->long,
            'file' =>  ($this->file == null) ? null : asset('storage/' . $this->file),
            'active' => (int)$this->status,
            'type'   =>(int)$this->type   
        ];
    }
}
