<?php

namespace App\Http\Resources;

use App\Models\Department;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
//            'okk' => $hello,
            'id' => $this->id ,
            'name' => $this->name,
             'image' => setImage($this->image),
        ];
    }
}
