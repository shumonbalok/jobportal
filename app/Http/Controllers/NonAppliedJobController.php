<?php

namespace App\Http\Controllers;

use App\Http\Resources\JobResource;
use App\Http\Resources\NonAppliedResource;
use App\Models\Job;
use App\Models\NonAppliedJob;
use App\Models\NonAppliedJobStatus;
use Illuminate\Http\Request;

class NonAppliedJobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $applied = auth()->user()->appliedJobs;
       $jobs =  Job::all();

       foreach($jobs as $key=>$job)
       {
           $job->status=$this->status($job);

           foreach($applied as $appliedJOb)
           {
                   if($job->id == $appliedJOb->job_id){
                       $jobs = $jobs->forget($key);
                   }
           }

       }

       return $this->apiResponseResourceCollection(200,'Non Applied Job list',NonAppliedResource::collection($jobs));
    }

    public function status($job)
    {
       $status  = [
                [
                'id' => 1,
                'description' => null,
                'short_name' => 'Preli',
                'lat' => null,
                'long'  => null,
                'file' => null,
                'active' => NonAppliedJobStatus::where([['job_id',$job->id],['type',1]])->first() !=  null ? 1 : 0,
                'type'   => 1
                ],
            [
                'id' => 2,
                'description' => null,
                'short_name' => 'Preli',
                'lat' => null,
                'long'  => null,
                'file' => null,
                'active' => NonAppliedJobStatus::where([['job_id', $job->id], ['type', 2]])->first() !=  null ? 1 : 0,
                'type'   => 2
            ],
            [
                'id' => 3,
                'description' => null,
                'short_name' => 'Written',
                'lat' => null,
                'long'  => null,
                'file' => null,
                'active' => NonAppliedJobStatus::where([['job_id', $job->id], ['type', 3]])->first() !=  null ? 1 : 0,
                'type'   => 3
            ],
            [
                'id' => 4,
                'description' => null,
                'short_name' => 'Written',
                'lat' => null,
                'long'  => null,
                'file' => null,
                'active' => NonAppliedJobStatus::where([['job_id', $job->id], ['type', 4]])->first() !=  null ? 1 : 0,
                'type'   => 4
            ],
            [
                'id' => 5,
                'description' => null,
                'short_name' => 'Viva',
                'lat' => null,
                'long'  => null,
                'file' => null,
                'active' => NonAppliedJobStatus::where([['job_id', $job->id], ['type', 5]])->first() !=  null ? 1 : 0,
                'type'   => 5
            ],
            [
                'id' => 6,
                'description' => null,
                'short_name' => 'Viva',
                'lat' => null,
                'long'  => null,
                'file' => null,
                'active' => NonAppliedJobStatus::where([['job_id', $job->id], ['type', 6]])->first() !=  null ? 1 : 0,
                'type'   => 6
            ],
       ]
       ;
       return $status;
    }

}
