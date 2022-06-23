<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HigherGraduateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $this->load('subject','university','major','passingYear','courseDuration');
        return [
            'name' => $this->name,
            // 'user' => UserResorce::collection($this->whenLoaded('users')),
            'subject' => SubjectResource::make($this->whenLoaded('subject')),
            'university' => UniversityResource::make($this->whenLoaded('university')) ,
            'roll_no' => $this->roll_no,
            'registration_no' => $this->registration_no,
            'result' => $this->result,
            'major' => MajorResource::make($this->whenLoaded('major')),
            'passing_year' =>PassingYearResource::make($this->whenLoaded('passingYear')),
            'courseDuration' => CourseDurationResource::make($this->whenLoaded('courseDuration')),
              'result_type' => (string)$this->result_type,
            'type'  =>$this->type,
            
        ];
    }
}
