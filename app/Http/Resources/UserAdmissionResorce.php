<?php

namespace App\Http\Resources;

use App\Models\AdmissionStatus;
use App\Models\UserAdmission;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAdmissionResorce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $cuurentStatus = AdmissionStatus::where('user_admission_id', $this->id)->where('status', 1)->latest('id')->first();
        $this->load('user','admission','admissionStatus');
        return [
            'id'        => $this->id,
            'roll' => $this->roll,
            'user' => UserResorce::make($this->user->load('basicInfo', 'experience', 'skill', 'address', 'graduates', 'higherGraduates', 'allphoto' , 'roles')),
            'Admission_Title' => AdmissionResource::make($this->admission),
            'admission_id'                => $this->admission->id,
            'name'              => $this->admission->name,
            'status'            => $this->admission->status == 1 ? 'Active' : 'Inactive',
            'unit'              => $this->admission->unit,
            'university'        => $this->admission->university ,
            'application_start' => $this->admission->application_start,
            'application_end'   => $this->admission->application_end,
            'exam_time'         => $this->admission->exam_time ? $this->exam_time : 'N\A',
            'application_fee'   => (string) $this->admission->application_fee,
            'service_fee'       => (string) $this->admission->service_fee,
            'min_gpa'           => $this->admission->min_gpa ? $this->admission->min_gpa : 'N\A',
            'min_gpa_total'     => $this->admission->min_gpa_total ? $this->admission->min_gpa_total : 'N\A',
            'required_group'    => $this->admission->group ,
            'file'              => $this->admission->file  ? $this->admission->file : 'N\A',
            'seat'              => $this->admission->seat ? $this->admission->seat : 'N\A',
            'current_status'    =>
            $cuurentStatus->short_name ?? null,
            'admisssionStatus'        => AdmissionStatusResource::collection($this->admissionStatus),
                'sent' => (int)$this->send_by_status,
            'admission_trnasfer' => (int)$this->admission_status
        ];
    }
}
