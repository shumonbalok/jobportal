<?php

namespace App\Http\Controllers;

use App\Http\Resources\AppliedJobResource;
use App\Http\Resources\BoardResource;
use App\Http\Resources\JobResource;
use App\Models\Address;
use App\Models\AppliedJob;
use App\Models\AppliedJobStatus;
use App\Models\Graduate;
use App\Models\Job;
use App\Models\Payment;
use App\Models\Subject;
use App\Models\User;
use App\Notifications\SuccesfullNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppliedJobController extends Controller
{

    public  function index()
    {

        $apppliedAllJob = AppliedJob::with([
            'jobs.department', 'jobs.examination', 'jobs.subject', 'jobs.grade', 'jobs.district', 'user.basicInfo',
            'user.address', 'user.experience', 'user.skill', 'user.graduates.examination', 'user.higherGraduates', 'user.allphoto',
            'user.allphotosub', 'appliedJobStatus' , 'jobs.payments'
        ])->when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->paginate(10);
        return $this->apiResponseResourceCollection(200, 'All Applied Job', AppliedJobResource::collection($apppliedAllJob));
    }
    public function store(Request $request, $id)
    {
        $district = null;
        $quota = '';
        $jobFind = null;
        $subject = null;
        $examnation = null;
        $district_id = null;
        $type = null;
        $subjectGroup = null;
        $major = null;
        $group = null;
        $higherGraduatesGroup = null;
        $job = Job::with(['jobDistricts', 'jobQuotas' , 'jobChecks'])->findOrFail($id);
        $fee = $job->fee + $job->service_fee;
        if ($request->user_id) {
            $user = User::with(['basicInfo', 'basicInfo.quota', 'address', 'experience', 'skill', 'graduates.examination', 'higherGraduates.subject', 'higherGraduates.major', 'allphoto', 'allphotosub'])->findOrFail($request->user_id);
        } else {
            $user = auth()->user()->load(['basicInfo', 'basicInfo.quota', 'address', 'experience', 'skill', 'graduates.examination', 'higherGraduates.subject', 'higherGraduates.major', 'allphoto', 'allphotosub']);
        }
        if ($user->profile_strength < 50) {
            return $this->apiResponse(404, 'Profile Update Please');
        }
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
        foreach (auth()->user()->appliedJobs()->pluck('job_id') ?? [] as $userJobFind) {
            if ($userJobFind == $id) {
                $jobFind = true;
            }
        }
        if($job->jobChecks  && $job->jobChecks->distric_check == 1){
            foreach ($job->jobDistricts as $Jobdistrict_id) {
                if ($district_id == $Jobdistrict_id->district_id) {
                    $district = true;
                }
            }
        }





        foreach ($job->jobQuotas as $jobQuotas_id) {
            if ($user->basicInfo->quota_id == $jobQuotas_id->quota_id) {
                $quota = $jobQuotas_id->quota_id;
            }
        }

        if( $job->jobChecks  && $job->jobChecks->distric_check == 1){
        if ($job->jobDistricts->count() != null && $district != $district_id)
        return $this->apiResponse(404, 'District Not Matched');
        }

  
            //check for home        
        // if ($job->jobQuotas->count() != null && $quota != $user->basicInfo->quota_id)
        //     return $this->apiResponse(404, 'Quota Not Matched');

        //Graduation type
        if ($job->type != null && $job->type != $type)
            return $this->apiResponse(404, 'Minimum Graduation/Post Graduation Not Matched');
        
        //Major
        if ($job->major_id != null && $job->major_id != $major)
            return $this->apiResponse(404, 'Required Major Not Matched Crossed');
        //Min Age

        if($job->jobChecks  && $job->jobChecks->min_age_check == 1){
            if ($job->min_age != null && $job->min_age > $age)
            return $this->apiResponse(404, 'Required Minimum age Limit Crossed');
        }

        //Max Age
        if($job->jobChecks  && $job->jobChecks->max_age_check == 1){
        if ($job->max_age != null && $job->max_age < $age)
            return $this->apiResponse(404, 'Required Maximum age Limit Crossed');
        }
        //Subject
        if ($job->subject_id != null && $job->subject_id != $subject)
            return $this->apiResponse(404, 'Required Graduation Subject not matched');
        //Examination
        if ($job->examination_id != null && $job->examination_id > $examnation) return $this->apiResponse(404, 'Required Non-Graduate Examination not matched');
        //Group
        if ($job->examination_id == null) {

            if ($job->group_id != null && $job->group_id != $higherGraduatesGroup)
                return $this->apiResponse(404, 'Graduate Background Group Are Not Matching ');
        } else {
            if ($job->group_id != null && $job->group_id != $group)

                return $this->apiResponse(404, 'Graduate Background Group Are Not Matching ');
        }
        //Gender
        if ($job->gender != null) {
            if ($job->gender != $user->basicInfo->gender) {
                return $this->apiResponse(404, 'Gender  Not Match');
            }
        }
        //Religion
        if ($job->religion != null) {
            if ($job->religion != $user->basicInfo->religion) {
                return $this->apiResponse(404, 'Religion  Not Match');
            }
        }
        //Maritial Status
        if ($job->marital_status != null) {
            if ($job->marital_status != $user->basicInfo->marital_status) {
                return $this->apiResponse(404, 'Marital status  Not Match');
            }
        }
        //Start Time
        if ($job->start_time != null && $job->start_time >= Carbon::now()) {
            return $this->apiResponse(404, 'Date  Not Start');
        }
        //End Time
        if ($job->end_time != null && $job->end_time <= Carbon::now()) {
            return $this->apiResponse(404, 'Date  iS  over');
        }
        // if ($job->jobDistricts->count() != null  && $district== false) {
        //     return $this->apiResponse(404, 'District Are Not Match');
        // }
        if ($jobFind == true) {
            return $this->apiResponse(404, 'Already Applied is Job');
        }

        if ($request->methods == Payment::ABEDOK) {
            $methods =  Payment::ABEDOK;
        } elseif ($request->methods == Payment::BKASH) {
            $methods = Payment::BKASH;
        } elseif ($request->methods  == Payment::ROCKET) {
            $methods = Payment::ROCKET;
        } elseif ($request->methods ==  Payment::CARD) {
            $methods = Payment::CARD;
        }
        
        if ($user->balance < $fee) {
            return $this->apiResponse(201, 'Balance Insuffcine');
        } else {
            $jobApplied = AppliedJob::create([
                'user_id' => $user->id,
                'job_id' => $id
            ]);
            $userPayment = $user->decrement('balance', $fee);
        }
        foreach ($user->payments()->pluck('job_id') as $jobId) {
            if ($jobId == $request->job_id) {
                return $this->apiResponse(201, 'Already Balance Send');
            }
        }
        $Successfully = [
            'title' => "Payment done",
            'message' => "Payment Was  Succsfully",
            'url' => ''
        ];
        $user->notify(new SuccesfullNotification($Successfully));
        
        $payments =   Payment::create([
            'user_id' => $user->id,
            'job_id' => $id,
            'balance' => $job->fee,
            'methods' =>  $methods,
            'transation_number' => $this->generateNumberCode(),
        ]);
        $jobApplied->appliedJobStatus()->create([
            'description' => "Application Submission",
            'short_name' => "Applied",
            'type'       => 0,
            'status'     => 1
        ]);
        $jobApplied->appliedJobStatus()->create([
            'description' => "Payment Successfull",
            'short_name' => "Applied",
            'type'       => 1,
            'status'     => 0
        ]);
        $jobApplied->appliedJobStatus()->create([
            'description' => "Exam Center Location",
            'short_name' => "Preli",
            'type'       => 2,
            'status'     => 0
        ]);
        $jobApplied->appliedJobStatus()->create([
            'description' => "Preli Result",
            'short_name' => "Preli",
            'type'       => 3,
            'status'     => 0
        ]);
        $jobApplied->appliedJobStatus()->create([
            'description' => "Written Exam Center Location",
            'short_name' => "Written",
            'type'       => 4,
            'status'     => 0
        ]);
        $jobApplied->appliedJobStatus()->create([
            'description' => "Written Exam Result",
            'short_name' => "Written",
            'type'       => 5,
            'status'     => 0
        ]);
        $jobApplied->appliedJobStatus()->create([
            'description' => "Viva Location",
            'short_name' => "Viva",
            'type'       => 6,
            'status'     => 0
        ]);
        $jobApplied->appliedJobStatus()->create([
            'description' => "Viva Result",
            'short_name' => "Viva",
            'type'       => 7,
            'status'     => 0
        ]);
        $jobApplied->appliedJobStatus()->create([
            'description' => "Congratulations",
            'short_name' => "Congrats",
            'type'       => 8,
            'status'     => 0
        ]);

        return $this->apiResponse(201, 'Applied Job Successfully');
    }






    public function show()
    {

        $users = auth()->user();

        if (auth()->user()->appliedJobs == null) {
            return $this->apiResponse(201, 'Nothing To job');
        }

        $appliedJob =   $users->appliedJobs->load([
            'jobs.department', 'jobs.examination', 'jobs.subject', 'jobs.grade', 'jobs.district', 'user.basicInfo',
            'user.address', 'user.experience', 'user.skill', 'user.graduates.examination', 'user.higherGraduates', 'user.allphoto',
            'user.allphotosub', 'appliedJobStatus' ,'jobs.payments'
        ]);



        return $this->apiResponseResourceCollection(200, 'All Board', AppliedJobResource::collection($appliedJob));
    }
    public function update(Request $request, AppliedJob $appliedJob)
    {
        $appliedJob->update(['roll' => $request->roll]);
        return $this->apiResponse(201, 'Roll Updated');
    }
    public function  CreateStatus(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
        ]);

        if ($request->hasFile('file')) {
            $fileName = Rand() . '.' . $request->file('file')->getClientOriginalExtension();

            $file = $request->file('file')->storeAs('appliedJob', $fileName, 'public');
        }


        $appliedJobStatus = AppliedJobStatus::create([
            'applied_job_id' => $id,
            'description' => $request->description,
            'short_name' => $request->short_name,
            'map' => $request->map,
            'file' => $file,
        ]);

        $job = Job::find($id)->update([
            'status' => 1
        ]);



        return $this->apiResponse(201, 'Status Create Successfully');
    }

    public function  UpdateStatus(Request $request, $id)
    {



        $appliedJobStatusUpdate = AppliedJobStatus::findOrFail($id);
        $file = $appliedJobStatusUpdate->file;
        if ($request->hasFile('file')) {
            $fileName = Rand() . '.' . $request->file('file')->getClientOriginalExtension();

            $file = $request->file('file')->storeAs('appliedJob', $fileName, 'public');
        }
        $appliedJobStatusUpdate->update([
            'file' => $file,
        ]);
        return $this->apiResponse(201, 'Status Update Successfully');
    }

    public function  ActiveStatus(Request $request, $id)
    {

           $appliedJob = AppliedJobStatus::with(['appliedJob.jobs', 'appliedJob.user'])->where('id', $id)->first();

      
        $appliedJob->update([
            'status' => AppliedJobStatus::ACTIVE,
        ]);
        // $SuccessfullyPaement = [
        //     'message' => $appliedJob->description. " ".$appliedJob->job->name,
        // ];

        // foreach($appliedJob->user as $allUsers){
        //     $allUsers->user->notify(new SuccesfullNotification($SuccessfullyPaement));
        // }
        return $this->apiResponse(201, 'Status Active Successfully');
    }
    public function delete($id)
    {
        $appliedJob = AppliedJob::findOrFail($id);
        $appliedJob->appliedJobStatus()->delete();
        return $this->apiResponse(201, 'Applied Job & Status Delete');
    }

    public function marchent()
    {
        $machent =  auth()->user()->appliedMarchentJobs()
            ->with([
                'jobs.department', 'jobs.examination', 'jobs.subject', 'jobs.grade', 'jobs.district', 'user.basicInfo', 'jobs.payments',
                'user.address', 'user.experience', 'user.skill', 'user.graduates.examination', 'user.higherGraduates', 'user.allphoto',
                'user.allphotosub', 'appliedJobStatus'
            ])->get();
        return $this->apiResponseResourceCollection(200, 'All Board', AppliedJobResource::collection($machent));
    }

    public function singleAppliedJob($id)
    {

        $appliedJob = AppliedJob::with([
            'jobs.department', 'jobs.examination', 'jobs.subject', 'jobs.grade', 'jobs.district', 'user.basicInfo', 'jobs.payments',
            'user.address', 'user.experience', 'user.skill', 'user.graduates.examination', 'user.higherGraduates', 'user.allphoto',
            'user.allphotosub', 'appliedJobStatus'
        ])->findOrFail($id);

        return $this->apiResponseResourceCollection(200, 'All Board', AppliedJobResource::make($appliedJob));
    }

    public function appliedJobPending()
    {
        $pendingJob = AppliedJob::with([
            'jobs.department', 'jobs.examination', 'jobs.subject', 'jobs.grade', 'jobs.district','jobs.payments', 'user.basicInfo',
            'user.address', 'user.experience', 'user.skill', 'user.graduates.examination', 'user.higherGraduates', 'user.allphoto',
            'user.allphotosub', 'appliedJobStatus'
        ])->get();

        return $this->apiResponseResourceCollection(200, 'All Board', AppliedJobResource::collection($pendingJob));
    }

    public function jobStatus($id)
    {
        $appliedJobStatusUpdate = AppliedJob::where('id', $id)->update([
             'status' => 1
        ]);

        return $this->apiResponse(201, 'Status Update Successfully');
    }

    public function generateNumberCode()
    {
        $transation_number = null;
        do {
            $transation_number =   'A' . (random_int(100000, 999999));
        } while (Payment::where("transation_number", "=", $transation_number)->first());
        return $transation_number;
    }


    public function appliedJobStatusUpdate( Request $request, $id){

        $request->validate([
            'roll' => 'required',
        ]);

        $appliedJob = AppliedJob::findOrFail($id);

        $appliedJob->update([
            'roll' => $request->roll
        ]);

        $appliedJobStatus = AppliedJobStatus::where(['applied_job_id' => $id , 'type' => 3])->first();
        if($appliedJobStatus == null){
            $appliedJob = DB::table('non_applied_job_statuses')->where(['job_id' => $appliedJob->job_id, 'type' => 1])->first();

            $appliedJobStatus->update([
                'lat' => $appliedJobStatus->lat,
                'long' => $appliedJobStatus->long,
                'status'     => 1
        ]);
        }
        return response()->json([
            'lat' => $appliedJobStatus->lat,
            'long' => $appliedJobStatus->long,
            'message' => 'ROll Update successfull',
        ], 201);
    }

    public function appliedJobStore(Request $request, $id)
    {




        $district = null;
        $quota = null;
        $jobFind = null;
        $subject = null;
        $examnation = null;
        $district_id = null;
        $type = null;
        $subjectGroup = null;
        $major = null;
        $group = null;
        $higherGraduatesGroup = null;
        $job = Job::with(['jobDistricts', 'jobQuotas' , 'jobChecks'])->findOrFail($id);
        $fee = $job->fee + $job->service_fee;

        $user = User::with(['basicInfo', 'basicInfo.quota', 'address', 'experience', 'skill', 'graduates.examination', 'higherGraduates.subject', 'higherGraduates.major', 'allphoto', 'allphotosub'])->findOrFail($request->user_id);

         if ($user->profile_strength < 50) {
             return $this->apiResponse(404, 'Profile Update Please');
         }
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
         foreach (auth()->user()->appliedJobs()->pluck('job_id') ?? [] as $userJobFind) {
             if ($userJobFind == $id) {
                 $jobFind = true;
             }
         }

         if($job->jobChecks  && $job->jobChecks->distric_check == 1){
             foreach ($job->jobDistricts as $Jobdistrict_id) {
                 if ($district_id == $Jobdistrict_id->district_id) {
                     $district = true;
                 }
             }
         }




         foreach ($job->jobQuotas as $jobQuotas_id) {
             if ($user->basicInfo->quota_id == $jobQuotas_id->quota_id) {
                 $quota = true;
             }
         }

         if( $job->jobChecks  && $job->jobChecks->distric_check == 1){
         if ($job->jobDistricts->count() != null && $district != $district_id)
         return $this->apiResponse(404, 'District Not Matched');
         }

        //work for home
        //  if ($job->jobQuotas->count() != null && $quota != $user->basicInfo->quota_id)
        //      return $this->apiResponse(404, 'Quota Not Matched');

         //Graduation type
         if ($job->type != null && $job->type != $type)
             return $this->apiResponse(404, 'Minimum Graduation/Post Graduation Not Matched');
         //Quota
         // if ($job->quota != null && $user->basicInfo->ff_quota == null)
         //     return $this->apiResponse(404, 'Required Qouta Not Matched');

         //Major
         if ($job->major_id != null && $job->major_id != $major)
             return $this->apiResponse(404, 'Required Major Not Matched Crossed');
         //Min Age

         if($job->jobChecks  && $job->jobChecks->min_age_check == 1){
             if ($job->min_age != null && $job->min_age > $age)
             return $this->apiResponse(404, 'Required Minimum age Limit Crossed');
         }

         //Max Age
         if($job->jobChecks  && $job->jobChecks->max_age_check == 1){
         if ($job->max_age != null && $job->max_age < $age)
             return $this->apiResponse(404, 'Required Maximum age Limit Crossed');
         }
         //Subject
         if ($job->subject_id != null && $job->subject_id != $subject)
             return $this->apiResponse(404, 'Required Graduation Subject not matched');
         //Examination
         if ($job->examination_id != null && $job->examination_id > $examnation) return $this->apiResponse(404, 'Required Non-Graduate Examination not matched');
         //Group
         if ($job->examination_id == null) {

             if ($job->group_id != null && $job->group_id != $higherGraduatesGroup)
                 return $this->apiResponse(404, 'Graduate Background Group Are Not Matching ');
         } else {
             if ($job->group_id != null && $job->group_id != $group)

                 return $this->apiResponse(404, 'Graduate Background Group Are Not Matching ');
         }
         //Gender
         if ($job->gender != null) {
             if ($job->gender != $user->basicInfo->gender) {
                 return $this->apiResponse(404, 'Gender  Not Match');
             }
         }
         //Religion
         if ($job->religion != null) {
             if ($job->religion != $user->basicInfo->religion) {
                 return $this->apiResponse(404, 'Religion  Not Match');
             }
         }
         //Maritial Status
         if ($job->marital_status != null) {
             if ($job->marital_status != $user->basicInfo->marital_status) {
                 return $this->apiResponse(404, 'Marital status  Not Match');
             }
         }
         //Start Time
         if ($job->start_time != null && $job->start_time >= Carbon::now()) {
             return $this->apiResponse(404, 'Date  Not Start');
         }
         //End Time
         if ($job->end_time != null && $job->end_time <= Carbon::now()) {
             return $this->apiResponse(404, 'Date  iS  over');
         }
         // if ($job->jobDistricts->count() != null  && $district== false) {
         //     return $this->apiResponse(404, 'District Are Not Match');
         // }
         if ($jobFind == true) {
             return $this->apiResponse(404, 'Already Applied is Job');
         }
        $marchent = auth()->user();

        // if($marchent->hasPermissionTo('user.access') == true){

            if ($request->methods == Payment::ABEDOK) {
                $methods =  Payment::ABEDOK;
            } elseif ($request->methods == Payment::BKASH) {
                $methods = Payment::BKASH;
            } elseif ($request->methods  == Payment::ROCKET) {
                $methods = Payment::ROCKET;
            } elseif ($request->methods ==  Payment::CARD) {
                $methods = Payment::CARD;
            }
                foreach ($user->payments()->pluck('job_id') as $jobId) {
                    if ($jobId == $request->job_id) {
                        return $this->apiResponse(201, 'Already Balance  Send');
                    }
                }

                if ($marchent->balance < $fee) {

                    $jobApplied = AppliedJob::create([
                        'user_id' => $user->id,
                        'job_id' => $id
                    ]);
                    $jobApplied->users()->attach($marchent->id);
                    $payments =   Payment::create([
                        'user_id' => $user->id,
                        'job_id' => $id,
                        'balance' => $job->fee,
                        'methods' =>  $methods,
                        'transation_number' => $this->generateNumberCode(),
                        'payement_status' =>  0,
                    ]);
                    $payments->workerPayments()->create([
                        'user_id' => $marchent->id,
                    ]);
                }else {
                    $comissionsMarchent  = $job->service_fee * ($marchent->commissions / 100);
                    $adminProfit = $job->service_fee - $comissionsMarchent;
                    $adminBalanceGet = $adminProfit + $job->fee;
                    $marchentPayment = $marchent->decrement('balance', $fee);
                    $marchentProfit = $marchent->increment('balance', $comissionsMarchent);
                    $marchentCommssionProfit = $marchent->increment('profit', $comissionsMarchent);
                    $admin = User::find(1)->increment('balance', $adminBalanceGet);
                    $adminProfit = User::find(1)->increment('profit', $adminProfit);
                    $jobApplied = AppliedJob::create([
                        'user_id' => $user->id,
                        'job_id' => $id,
                        'status' => 1
                    ]);
                    $jobApplied->users()->attach($marchent->id);
                    $payments =   Payment::create([
                        'user_id' => $user->id,
                        'job_id' => $id,
                        'balance' => $job->fee,
                        'methods' =>  $methods,
                        'transation_number' => $this->generateNumberCode(),
                        'payement_status' =>  1,
                    ]);
                    $payments->workerPayments()->create([
                        'user_id' => $marchent->id,
                    ]);
                }

        //  }else{

        //     $jobApplied = AppliedJob::create([
        //         'user_id' => $user->id,
        //         'job_id' => $id
        //     ]);
        // }

        $Successfully = [
            'title' => 'Payment Done',
            'message' => "Payment Was  Succsfully",
            'url' => '/job/merchent/apply',
        ];
        $marchent->notify(new SuccesfullNotification($Successfully));
        $user->notify(new SuccesfullNotification($Successfully));

         $jobApplied->appliedJobStatus()->create([
             'description' => "Application Submission",
             'short_name' => "Applied",
             'type'       => 0,
             'status'     => 1
         ]);
         $jobApplied->appliedJobStatus()->create([
             'description' => "Payment Successfull",
             'short_name' => "Applied",
             'type'       => 1,
             'status'     => 0
         ]);
         $jobApplied->appliedJobStatus()->create([
             'description' => "Exam Center Location",
             'short_name' => "Preli",
             'type'       => 2,
             'status'     => 0
         ]);
         $jobApplied->appliedJobStatus()->create([
             'description' => "Preli Result",
             'short_name' => "Preli",
             'type'       => 3,
             'status'     => 0
         ]);
         $jobApplied->appliedJobStatus()->create([
             'description' => "Written Exam Center Location",
             'short_name' => "Written",
             'type'       => 4,
             'status'     => 0
         ]);
         $jobApplied->appliedJobStatus()->create([
             'description' => "Written Exam Result",
             'short_name' => "Written",
             'type'       => 5,
             'status'     => 0
         ]);
         $jobApplied->appliedJobStatus()->create([
             'description' => "Viva Location",
             'short_name' => "Viva",
             'type'       => 6,
             'status'     => 0
         ]);
         $jobApplied->appliedJobStatus()->create([
             'description' => "Viva Result",
             'short_name' => "Viva",
             'type'       => 7,
             'status'     => 0
         ]);
         $jobApplied->appliedJobStatus()->create([
             'description' => "Congratulations",
             'short_name' => "Congrats",
             'type'       => 8,
             'status'     => 0
         ]);

        return $this->apiResponse(201, 'Applied Job Successfully');
    }

}
