<?php

namespace App\Imports;

use App\Models\AppliedJob;
use App\Models\AppliedJobStatus;
use App\Models\NonAppliedJob;
use App\Models\NonAppliedJobStatus;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class JobImport implements ToModel , WithStartRow , WithChunkReading, ShouldQueue , WithBatchInserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */ protected $id,$type;

    function __construct($id,$type)
    {
        $this->id = $id;
        $this->type=$type;
    }
        public function startRow(): int
        {
            return 2;
        }

    public function model(array $row )
    {

        $this->appliedJob($this->id,$row[0],$this->type,$row[2],$row[3]);
        return new NonAppliedJobStatus([
           'roll_number'     => $row[0],
           'status'    => $row[1],
           'lat' => $row[2],
           'long' => $row[3],
           'job_id' =>$this->id,
           'type'   =>$this->type

        ]);
    }

    public function rules(): array
    {
        return [
            0 => ['required'],
            1 => ['nullable'],
            2 => ['nullable'],
            3 => ['nullable'],
            4 => ['required'],
            5 => ['required']
        ];
    }

    public function customValidationMessages()
    {
        return [
            0 => 'roll_number Not Found for .',
            1 => 'address Not Found for .',
            2 => 'lat Not Found for ',
            3 => 'lang Not Found for ',
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 1000;
    }
    public function appliedJob($job_id,$roll,$type,$lat,$long)
    {
        $job=AppliedJob::where('job_id',$job_id)->where('roll',$roll)->first();
      if($job!=null){
        if($type==NonAppliedJobStatus::PRELI_EXAM_LOCATION)
           {

            AppliedJobStatus::where('applied_job_id',$job->id)->where('type',AppliedJobStatus::PRELI_LOCATION)->update(['lat'=>$lat,'long'=>$long,'status'=>1]);
           }
        elseif ($type == NonAppliedJobStatus::PRELI_RESULT) {
            AppliedJobStatus::where('applied_job_id', $job->id)->where('type', AppliedJobStatus::PRELI_RESULT)->update([ 'status' => 1]);
        }
        elseif($type == NonAppliedJobStatus::WRITTEN_EXAM_LOCATION)
        {
            AppliedJobStatus::where('applied_job_id', $job->id)->where('type', AppliedJobStatus::WRITTEN_LOCATION)->update(['lat' => $lat, 'long' => $long, 'status' => 1]);
        }
        elseif ($type == NonAppliedJobStatus::WRITTEN_RESULT) {
            AppliedJobStatus::where('applied_job_id', $job->id)->where('type', AppliedJobStatus::WRITTEN_RESULT)->update(['status' => 1]);
        }
        elseif ($type == NonAppliedJobStatus::VIVA_LOCATION)
        {
            AppliedJobStatus::where('applied_job_id', $job->id)->where('type', AppliedJobStatus::VIVA_LOCATION)->update(['lat' => $lat, 'long' => $long, 'status' => 1]);
        }
        elseif ($type == NonAppliedJobStatus::VIVA_RESULT) {
            AppliedJobStatus::where('applied_job_id', $job->id)->where('type', AppliedJobStatus::VIVA_RESULT)->update(['status' => 1]);
        }
      }
    }

}
