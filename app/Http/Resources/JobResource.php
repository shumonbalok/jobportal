<?php

namespace App\Http\Resources;

use App\Http\Controllers\JobController;
use App\Models\Address;
use App\Models\AppliedJobStatus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $list = $this->eligibleUsers($this);
         $this->load('jobChecks', 'department', 'examination', 'subject', 'grade', 'jobDistricts', 'groups', 'post', 'jobQuotas');

        return [
            'id' => $this->id,
            'name' => $this->name,
            // 'post'  =>$this->post->name,
            'max_age' => $this->max_age,
            'min_age' => $this->min_age,
            'sit' => $this->sit,
            'department' => DepartmentResource::make($this->whenLoaded('department')),
            'examination' => ExaminationResource::make($this->whenLoaded('examination')),
            'subject'  => SubjectResource::make($this->whenLoaded('subject')),
            'grade' => GradeResource::make($this->whenLoaded('grade')),
            'district' => JobDistrictResource::collection($this->whenLoaded('jobDistricts')),
            'group' => GroupResource::make($this->whenLoaded('groups')),
            'post' => PostResource::make($this->whenLoaded('post')),
            'quota' => QuotaResource::collection($this->whenLoaded('jobQuotas')),
            'fee' => $this->fee,
            'service_fee' => $this->service_fee,
            'start_time'  => Carbon::parse($this->start_time)->format('Y-m-d'),
            'end_time' => Carbon::parse($this->end_time)->format('Y-m-d'),
            'is_expirirng'  => Carbon::parse($this->end_time)->diffInHours(now()) <= 72 ? 1 : 0,
            'description' => $this->description,
            'link'  => $this->link,
            'skill'  => $this->skill,
            'experience'  => $this->experience,
            'experience_details'  => $this->experience_details,
            'Checks'  => JobCheckResource::make($this->whenLoaded('jobChecks')),
            'status' => $this->status== 0 ? 'Active': 'Inactive',
            'eligible_users'    => count($list),
            'sharing_url'       => $this->sharing_url
        ];
    }
    public function check($job, $user)
    {
        $district = 0;
        $quota = 0;
        $jobFind = null;
        $subject = null;
        $examnation = null;
        $district_id = null;
        $quota_id = null;
        $type = null;
        $subjectGroup = null;
        $major = null;
        $group = null;
        $higherGraduatesGroup = null;
        // $job = Job::with('jobDistricts')->findOrFail($id);
        $fee = $job->fee + $job->service_fee;
        // if ($request->user_id) {
        //     $user = User::with(['basicInfo', 'address', 'experience', 'skill', 'graduates.examination', 'higherGraduates.subject', 'higherGraduates.major', 'allphoto', 'allphotosub'])->findOrFail($request->user_id);
        // } else {

        $user->load(['basicInfo', 'basicInfo.quota', 'address', 'experience', 'skill', 'graduates.examination', 'higherGraduates.subject', 'higherGraduates.major', 'allphoto', 'allphotosub']);
        if ($user->profile_strength < 40 || $user->basicInfo == null)
            return 1;
        // }
        // if ($user->profile_strength < 50) {
        //     return $this->apiResponse(404, 'Profile Update Please');
        // }

        $age = Carbon::parse($user->basicInfo->birth_date)->age;


        foreach ($user->address as $address) {
            if ($address->type == Address::PERMANENT || $address->type == Address::SAME) {
                if ($address->district_id != null) {
                    $district_id = $address->district_id;
                }
            }
        }
        foreach ($user->graduates as $graduates) {
            if ($job->examination_id != null) {
                $examnation = $graduates->examination_id;
                if ($examnation == $job->examination_id) {
                    if ($job->group_id != null) {
                        $group = $graduates->group_id;
                    }
                }
            }
        }
        foreach ($user->higherGraduates as $higherGraduates) {
            if ($job->subject_id != null) {
                $higherGraduatesGroup = $higherGraduates->subject?->group_id;
                $type = $higherGraduates->type;
                $major = $higherGraduates->major?->id ;
                if ($job->subject_id != null && $job->subject_id == $higherGraduates->subject_id) {
                    $subject = $higherGraduates->subject_id;
                }
            }
        }
        foreach ($user->appliedJobs()->pluck('job_id') ?? [] as $userJobFind) {
            if ($userJobFind == $job->id) {
                $jobFind = true;
            }
        }

        $job->load('jobDistricts');
        $job->load('jobQuotas');
        $job->load('jobChecks');

            foreach ($job->jobDistricts as $Jobdistrict_id) {
                if ($district_id == $Jobdistrict_id->district_id) {
                    $district = true;
                }
            }

        foreach ($job->jobQuotas as $jobQuotas_id) {
            if ($user->basicInfo->quota_id == $jobQuotas_id->quota_id) {
                $quota= $jobQuotas_id->quota_id;
            }
        }

        if($job->jobChecks  && $job->jobChecks->distric_check == 1){
            if ($job->jobDistricts->count() != null && $district != $district_id)
                return false;
        }


        if ($job->jobQuotas->count() != null && $quota != $user->basicInfo->quota_id)
            return false;

        //Graduation type
        if ($job->type != null && $job->type != $type)
            return false;
        //Quota


        //Major
        if ($job->major_id != null && $job->major_id != $major)
            return false;
        //Min Age

        if( $job->jobChecks  && $job->jobChecks->min_age_check == 1){
        if ($job->min_age != null && $job->min_age > $age)
            return false;
        }

        //Max Age
        if($job->jobChecks  && $job->jobChecks->max_age_check == 1){
        if ($job->max_age != null && $job->max_age < $age)
            return false;
        }
        //Subject
        if ($job->subject_id != null && $job->subject_id != $subject)
            return false;
        //Examination
        if ($job->examination_id != null && $job->examination_id > $examnation)
            return false;
        //Group
        if ($job->examination_id == null) {

            if ($job->group_id != null && $job->group_id != $higherGraduatesGroup)
                return false;
        } else {
            if ($job->group_id != null && $job->group_id != $group) {
                return false;
            }
        }
        //Gender
        if ($job->gender != null) {
            if ($job->gender != $user->basicInfo->gender) {
                return false;
            }
        }
        //Religion
        if ($job->religion != null) {
            if ($job->religion != $user->basicInfo->religion) {
                return false;
            }
        }
        //Maritial Status
        if ($job->marital_status != null) {
            if ($job->marital_status != $user->basicInfo->marital_status) {
                return false;
            }
        }
        //Start Time
        if ($job->start_time != null && $job->start_time >= Carbon::now()) {
            return false;
        }
        //End Time
        if ($job->end_time != null && $job->end_time <= Carbon::now()) {
            return false;
        }
        // if ($job->jobDistricts->count() != null  && $district == false) {
        //     // dd('Not Required Districts',$user->id);
        // }
        if ($jobFind == true) {
            return false;
        }
        return 'Passed';
    }

    public function eligibleUsers($job)
    {

        $users = User::where('type',1)->get();
        foreach ($users as $key => $user) {
            $check = $this->check($job, $user);
            if ($check != 'Passed') $users = $users->forget($key);
        }
        return $users;
    }
}
