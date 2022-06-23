<?php

namespace App\Http\Resources;

use App\Models\Graduate;
use App\Models\UserAdmission;
use Illuminate\Http\Resources\Json\JsonResource;

class AdmissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    
    public function toArray($request)
    {
        $group_id = $this->group_id;
        $list = [];
        // dd($this->userAdmission->count());

        $users = Graduate::where([['examination_id', 3], ['group_id', $group_id]])->with('users')->get();
        foreach ($users as $key => $user) {
            $gpa = null;
            $min_gpa = null;
            $user->users->load('graduates');
            foreach ($user->users->graduates as $graduate) {
                $graduate->load('examination', 'group');
                if ($graduate->examination->name == 'SSC' || $graduate->examination->name == 'HSC') {
                    if ($graduate->examination->name == 'HSC') {
                        $min_gpa = $graduate->result;
                    }
                    $gpa += $graduate->result;
                }
            }
            if ($this->min_gpa && $this->min_gpa <= $min_gpa && $this->min_gpa_total && $this->min_gpa_total <= $gpa) {
                $check = UserAdmission::where('admission_id', $this->id)->where('user_id', $user->users->id)->first();
                if ($check == null)
                    $list[$key] = $user->users->load('basicInfo', 'experience', 'skill', 'address', 'graduates', 'higherGraduates', 'allphoto');
            }
        }
        $this->load('unit','university','group', 'admissionQuotas');
        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'status'            => $this->status,
            'unit'              => UniteResource::make($this->whenLoaded('unit')),
            'university'        => UniversityResource::make($this->whenLoaded('university')),
            'application_start' => $this->application_start,
            'application_end'   => $this->application_end,
            'exam_time'         => $this->exam_time ? $this->exam_time : 'N\A',
            'application_fee'   => (string) $this->application_fee,
            'service_fee'       => (string) $this->service_fee,
            'min_gpa'           => $this->min_gpa ? $this->min_gpa : 'N\A',
            'min_gpa_total'     => $this->min_gpa_total ? $this->min_gpa_total : 'N\A',
            'required_group'    => $this->group ? GroupResource::make($this->whenLoaded('group')) : 'N\A',
            'file'              => $this->file  ? $this->file : 'N\A',
            'seat'              => (string)$this->seat ? $this->seat : 'N\A',
            'quota'             => QuotaResource::collection($this->whenLoaded('admissionQuotas')),
            'eligible_user'     => count($list)

        ];
    }
}
