<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AdmissionHistroyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
         $this->load('user','admission');
        return [
            'id'                => $this->admission->id,
            'name'              => $this->admission->name,
            'status'            => $this->admission->status,
            'unit'              =>  $this->admission->unit,
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
            'quota'             => $this->admission->admissionQuotas,
            'eligible_user'     => 0
            ];
    }
}
