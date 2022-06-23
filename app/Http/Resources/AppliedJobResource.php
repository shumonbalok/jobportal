<?php

namespace App\Http\Resources;

use App\Models\AppliedJobStatus;
use Illuminate\Http\Resources\Json\JsonResource;

class AppliedJobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $cuurentStatus = AppliedJobStatus::where('applied_job_id', $this->id )->where('status' , 1)->latest('id')->first();
        $this->load('jobs.payments');
        return [
            'id' =>$this->id,
            'user' => UserResorce::make($this->whenLoaded('user')),
            'job_id' => $this->jobs->id,
            'job_name' => $this->jobs->name,
            'roll'  =>$this->roll,
            'Department' => $this->jobs->department->name,
            'grade' => $this->jobs->grade->name,
            'fee' => $this->jobs->fee,
            'service_fee' => $this->jobs->service_fee,
            'date' => $this->created_at,
            'current_status' => $cuurentStatus->short_name ?? null,
            'appliedJobStatus' => AppliedJobStatusResource::collection($this->whenLoaded('appliedJobStatus')),
            'payments' => $this->jobs->payments,
            'status'=> (int)$this->status,
            'send'=> (int)$this->send_by_status,
            
        ];
    }
}
