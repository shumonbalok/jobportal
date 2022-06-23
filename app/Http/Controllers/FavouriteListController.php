<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdmissionHistroyResource;
use App\Http\Resources\AdmissionResource;
use App\Http\Resources\AppliedJobResource;
use App\Http\Resources\JobResource;
use App\Http\Resources\UserAdmissionResorce;
use App\Models\Address;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Admission;
use App\Models\Graduate;
use App\Models\UserAdmission;

class FavouriteListController extends Controller
{
    public  function  index()
    {
        $userFavouriteDepatment =  auth()->user()->userFavouriteJobs->load('departments.jobs.appliedJobs'); // job 1
        $user = auth()->user();
        $userFavouriteGrade =  auth()->user()->userFavouriteGrade->load('grade.jobs.appliedJobs'); // job 1
        $dp = [];

        foreach ($userFavouriteDepatment as $userFavouriteDepatments) {
            foreach ($userFavouriteDepatments->departments->jobs as $key => $job) {
                $check = $this->check($job, $user);
                if ($check == 'Passed')
                    $dp[] = $job;
            }
            foreach ($userFavouriteGrade as $userFavouriteGrades) {
                foreach ($userFavouriteGrades->grade->jobs as $key => $job) {
                    $check = $this->check($job, $user);
                    if ($check == 'Passed')
                        $dp[] = $job;
                }
            }
        }
        // $favouriteList = array_unique(array_merge($dp,$gd));
        return $this->apiResponseResourceCollection(200, 'All Jobs', JobResource::collection($dp));
    }

    public function admissionFavourites()
    {
        $userFavouriteDepatments =  auth()->user()->userFavouriteUniversities()->with('universities.admission')->get();
        $applied = auth()->user()->userAdmission()->get();
        $gpa = null;
        $min_gpa = null;
        $dp=[];
        $group_id=null;
        foreach ($userFavouriteDepatments as $userFavouriteDepatment) {
            $admissions = $userFavouriteDepatment->universities->admission;
                foreach (auth()->user()->graduates as $graduate) {
                    $graduate->load('examination', 'group');
                    if ($graduate->examination->name == 'SSC' || $graduate->examination->name == 'HSC') {
                        if ($graduate->examination->name == 'HSC') {
                            $min_gpa = $graduate->result;
                            $group_id=$graduate->group_id;
                        }
                        $gpa += $graduate->result;
                    }
                }
                foreach ($admissions as $key => $admission) {
                    if ($admission->group_id && $admission->group_id!=$group_id) {
                        $admissions = $admissions->forget($key);
                    }
                    if ($admission->min_gpa > $min_gpa && $admission->min_gpa_total > $gpa) {
                        $admissions = $admissions->forget($key);
                    }
                    foreach ($applied as $key=> $apply) {
                        if ($apply->admission_id == $admission->id) {
                            $admissions = $admissions->forget($key);
                        }
                    }
                }
                foreach($admissions as $admision)
                {
                    $dp[]=$admision;
                }
        }
        return $this->apiResponseResourceCollection(200, 'All Admission', AdmissionResource::collection($dp));
    }


    public  function history()
    {
        $historys = auth()->user()->appliedJobs->load('jobs.department', 'jobs.grade', 'appliedJobStatus', 'user');
        return $this->apiResponseResourceCollection(200, 'All History', AppliedJobResource::collection($historys));
    }
    public  function admissionHistory()
    {
        $historys = auth()->user()->userAdmission->load('admission.unit' , 'admission.group' , 'admission.university' );

        return $this->apiResponseResourceCollection(200, 'All History', AdmissionHistroyResource::collection($historys));
    }
    public function check($job, $user)
    {
        $district = 0;
        $jobFind = null;
        $subject = null;
        $examnation = null;
        $district_id = null;
        $type = null;
        $subjectGroup = null;
        $major = null;
        $group = null;
        $quota=null;
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
                $higherGraduatesGroup = $higherGraduates->subject->group_id;
                $type = $higherGraduates->type;
                $major = $higherGraduates->major->id;
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

        if ($job->jobChecks  && $job->jobChecks->distric_check == 1) {
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
        if ($job->jobChecks  && $job->jobChecks->min_age_check == 1) {
            if ($job->min_age != null && $job->min_age > $age)
                return false;
        }
        //Max Age
        if ($job->jobChecks  && $job->jobChecks->max_age_check == 1) {
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
}
