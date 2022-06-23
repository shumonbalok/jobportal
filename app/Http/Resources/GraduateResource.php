<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GraduateResource extends JsonResource
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
            'name' => $this->name,
            // 'user' => UserResorce::collection($this->whenLoaded('users')),
            'examination' => ExaminationResource::make($this->whenLoaded('examination')),
            'board' => BoardResource::make($this->whenLoaded('board')) ,
            'roll_no' => $this->roll_no,
            'registration_no' => $this->registration_no,
            'result' => $this->result,
            'passing_year' =>PassingYearResource::make($this->whenLoaded('passingYear')),
            'group' => GroupResource::make($this->whenLoaded('group')),
            'result_type' => (string)$this->result_type,
        ];
    }
}
