<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobRequest;
use App\Http\Resources\AppliedJobResource;
use App\Http\Resources\JobResource;
use App\Http\Resources\JobWithOutUserResource;
use App\Http\Resources\UserResorce;
use App\Imports\ExcelUploadImport;
use App\Models\AppliedJob;
use App\Models\Job;
use Illuminate\Pagination\Paginator;
use App\Models\Address;
use App\Models\Admission;
use App\Models\JobDistrict;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $job = Job::with('department', 'examination', 'subject', 'grade', 'post', 'jobDistricts.districts', 'groups', 'jobQuotas')->when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->paginate(10);
        // $job->load('department');
        // $job->load('examination');
        // $job->load('subject');
        // $job->load('post');
        // $job->load('grade');
        // $job->load('jobDistricts.districts');
        // dd($job);
        return $this->apiResponseResourceCollection(200, 'All Jobs', JobResource::collection($job));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JobRequest $request)
    {

        // dd($request->all());

        $jobValidate =  $request->validated();
        $jobValidate['start_time'] = Carbon::parse($request->start_time)->format('Y-m-d');
        $jobValidate['end_time'] = Carbon::parse($request->end_time)->format('Y-m-d');


        $jobs = Job::create($jobValidate);

        if ($request->district_id != null) {
            foreach ($request->district_id as $value) {
                $jobs->jobDistricts()->create([
                    'district_id' => $value,
                ]);
            }
        }
        if ($request->quota_id != null) {
            foreach ($request->quota_id as $value) {
                $jobs->jobQuotas()->attach([
                    'quota_id' => $value,
                ]);
            }
        }

        if($request->quota_id != null){
            $jobs->jobChecks()->create([
                'min_age_check' => $request->min_age_check,
                'max_age_check' => $request->max_age_check,
                'distric_check' => $request->distric_check,
                'skill_check' => $request->skill_check,
                'experience_check' => $request->experience_check,
            ]);
        }

        return $this->apiResponse(201, 'Job create Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show(Job $job)
    {
        $job->load('department');
        $job->load('examination');
        $job->load('subject');
        $job->load('post');
        $job->load('grade');
        $job->load('groups');
        $job->load('jobDistricts.districts');
        return $this->apiResponseResourceCollection(201, 'Job Info', JobResource::make($job));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit(Job $job)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update(JobRequest $request, Job $job)
    {
        $jobValidate =  $request->validated();
        $jobValidate['start_time'] = Carbon::parse($request->start_time)->format('Y-m-d');
        $jobValidate['end_time'] = Carbon::parse($request->end_time)->format('Y-m-d');
        $job->update($jobValidate);
        if ($request->district_id != null) {
            $job->jobDistricts()->delete();
            foreach ($request->district_id as $value) {
                $job->jobDistricts()->create([
                    'district_id' => $value,
                ]);
            }
        } else {
            $job->jobDistricts()->delete();
        }

        if ($request->quota_id != null) {
            foreach ($request->quota_id as $value) {
                $job->jobQuotas()->sync([
                    'quota_id' => $value,
                ]);
            }
            $job->jobChecks()->update([
                'min_age_check' => $request->min_age_check,
                'max_age_check' => $request->max_age_check,
                'distric_check' => $request->distric_check,
                'skill_check' => $request->skill_check,
                'experience_check' => $request->experience_check
            ]);
        }else{
             $job->jobQuotas()->delete();
        }


        return $this->apiResponse(201, 'Job Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy(Job $job)
    {
        $job->delete();
        return $this->apiResponse(201, 'Job Deleted Successfully');
    }

    // public function forceDelete($id)
    // {
    //     $district= Job::withTrashed()->find($id);
    //     $district->forceDelete();
    //     return $this->apiResponse(201, 'Job Delete Successfully');
    // }

    public  function active()
    {


        $job = Job::where('status', Job::ACTIVE)->paginate(10);
        $job->load('department');
        $job->load('examination');
        $job->load('subject');
        $job->load('grade');
        $job->load('district.upazila.postOffice');
        $job->load('appliedJobs');
        return $this->apiResponseResourceCollection(200, 'All Jobs', JobResource::collection($job));
    }

    public function isInActive($id)
    {
        $appliedJob = Job::findOrFail($id);
        $appliedJob->update([
            'status' => Job::INACTIVE
        ]);

        return $this->apiResponse(201, 'Job INACTIVE ');
    }
    public function nonApplied()
    {
        $jobs = Job::where('status', Job::ACTIVE)->with('department', 'examination', 'subject', 'grade', 'post', 'jobDistricts.districts', 'groups')->get();
        $applied = auth()->user()->appliedJobs()->get();
        $user = auth()->user();
        if ($user->profile_strength == 0)
            return $this->apiResponseResourceCollection(200, 'All Jobs', JobResource::collection($jobs));
        else {
            $list[] = null;
            $ok = null;

            foreach ($jobs as $key => $job) {
                $check = $this->check($job, $user);
                if ($check == false) $jobs = $jobs->forget($key);
            }

            $ok = 0;

            return $this->apiResponseResourceCollection(200, 'All Jobs', JobResource::collection($jobs));
        }
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
                $major = $higherGraduates->major?->id;
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

        if( $job->jobChecks  && $job->jobChecks->distric_check == 1){
            if ($job->jobDistricts->count() != null && $district != $district_id)
                return false;
        }

        // if( $job->jobChecks  && $job->jobChecks->quota_check == 1){
        //     if ($job->jobQuotas->count() != null && $quota != $user->basicInfo->quota_id)
        //         return false;
        // }
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
        if( $job->jobChecks  && $job->jobChecks->max_age_check == 1){
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
    public function eligibleUsers(Job $job)
    {

        $users = User::where('type', 1)->get();
        foreach ($users as $key => $user) {
            $check = $this->check($job, $user);
            // dd($check,$user->id);
            if ($check != 'Passed') $users = $users->forget($key);
        }
        $data = $this->paginate($users);
        return $this->apiResponseResourceCollection(200, 'Eligible Users List', UserResorce::collection($data));
    }
    public function paginate($items, $perPage = 4, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    // public function nonApplied()
    // {
    //     $jobs = Job::where('status', Job::ACTIVE)->get();
    //     $applied = auth()->user()->appliedJobs()->get();
    //     $list[] = null;
    //     $ok = null;
    //     foreach ($jobs as $key => $job) {
    //         foreach ($applied as $apply) {
    //             if ($apply->job_id == $job->id)

    //                 $ok = 1;
    //         }
    //         if ($ok == 1) {
    //             $jobs = $jobs->forget($key);
    //         }
    //         $ok = 0;
    //     }
    //     return $this->apiResponseResourceCollection(200, 'All Jobs', JobResource::collection($jobs));
    // }

    // public function jobWithoutUser(){
    //     $jobs = Job::where('status', Job::ACTIVE)->paginate(20);
    //     $jobs->load('department');
    //     $jobs->load('examination');
    //     $jobs->load('subject');
    //     $jobs->load('post');
    //     $jobs->load('grade');
    //     $jobs->load('district.upazila.postOffice');
    //     $jobs->load('appliedJobs');

    //     $applieds = auth()->user()->appliedJobs()->get();
    //     $withoutJobs = collect();

    //     foreach($jobs as $job){
    //         foreach($applieds as $applied){
    //             if($job->id != $applied->job){
    //                 $withoutJobs= $job;
    //             }
    //         }
    //     }

    //     dd( $withoutJobs);

    //     return $this->apiResponseResourceCollection(200, 'Job Fillter', JobWithOutUserResource::collection($job));
    // }

    // public function usermatching($id){

    //     $job = Job::findOrFail($id);
    //     $users = User::with(['basicInfo', 'address', 'experience', 'skill', 'graduates.examination', 'higherGraduates.subject','higherGraduates.major', 'allphoto', 'allphotosub'])->where('type', 1)->get();



    //     // foreach($users->gender as $gender){
    //     //     if($gender == $job->gender){
    //     //         $gender =
    //     //     }
    //     // }

    // }

    public function jobFillter(Request $request)
    {
        $jobFillter = Job::where(['department_id' => $request->department_id])->orWhere(['grade_id' => $request->grade_id])->orWhere('post_id' , $request->post_id)->get();
        return $this->apiResponseResourceCollection(200, 'Job Fillter', JobResource::collection($jobFillter));
    }

    public function jobHistoryFillter(Request $request)
    {
        $jobFillter = AppliedJob::with([
        'jobs.jobChecks',
        'jobs'=> function ($q){
            $q->where('post_id',  request()->input('post_id'));
            $q->orWhere('department_id',  request()->input('department_id'));
            $q->orWhere('grade_id',request()->input('grade_id'));
        },
     ])->where('user_id' , auth()->id())->get();
        return $this->apiResponseResourceCollection(200, 'Job Fillter', AppliedJobResource::collection($jobFillter));
    }
}
