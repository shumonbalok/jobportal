<?php

namespace App\Http\Resources;
use Carbon\Carbon;

use Illuminate\Http\Resources\Json\JsonResource;

class JobWithOutUserResource extends JsonResource
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
            'name' => $this->name ,
           // 'post'  =>$this->post->name,
            'max_age' => $this->max_age ,
            'min_age' => $this->min_age,
            'sit' => $this->sit,
            'department' => DepartmentResource::make($this->whenLoaded('department')),
            'examination' => ExaminationResource::make($this->whenLoaded('examination')),
            'subject'  => SubjectResource::make($this->whenLoaded('subject')),
            'grade' => GradeResource::make($this->whenLoaded('grade')),
            'district' => JobDistrictResource::collection($this->whenLoaded('jobDistricts')),
            'fee' => $this->fee,
            'start_time'  => Carbon::parse($this->start_time)->format('Y-m-d'),
            'end_time' => Carbon::parse($this->end_time)->format('Y-m-d'),
            'description' => $this->description ,
            'link'  => $this->link,
            'skill'  => $this->skill,
            'experience'  => $this->experience,
            'status' => $this->status== 0 ? 'Active': 'Inactive',
        ];
    }
}
