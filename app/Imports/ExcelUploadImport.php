<?php

namespace App\Imports;

use App\Models\AdmissionStatus;
use App\Models\ExcelUpload;
use App\Models\UserAdmission;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;


class ExcelUploadImport implements ToModel , WithStartRow , WithChunkReading, ShouldQueue , WithBatchInserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    protected $id,$type;

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
        $this->appliedAdmission($this->id, $row[0], $this->type, $row[2], $row[3]);
        return new ExcelUpload([
           'roll_number'     => $row[0],
           'status'    => $row[1],
           'lat' => $row[2],
           'long' => $row[3],
           'admission_id' =>$this->id,
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
            3 => 'long Not Found for ',
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
    public function appliedAdmission($job_id, $roll, $type, $lat, $long)
    {
        $job = UserAdmission::where('admission_id', $job_id)->where('roll', $roll)->first();
        if ($job != null) {
            if ($type == ExcelUpload::PRELI_EXAM_LOCATION) {

                AdmissionStatus::where('user_admission_id', $job->id)->where('type', AdmissionStatus::PRELI_LOCATION)->update(['lat' => $lat, 'long' => $long, 'status' => 1]);
            } elseif ($type == ExcelUpload::PRELI_RESULT) {
                AdmissionStatus::where('user_admission_id', $job->id)->where('type', AdmissionStatus::PRELI_RESULT)->update(['status' => 1]);
            } elseif ($type == ExcelUpload::WRITTEN_EXAM_LOCATION) {
                AdmissionStatus::where('user_admission_id', $job->id)->where('type', AdmissionStatus::WRITTEN_LOCATION)->update(['lat' => $lat, 'long' => $long, 'status' => 1]);
            } elseif ($type == ExcelUpload::WRITTEN_RESULT) {
                AdmissionStatus::where('user_admission_id', $job->id)->where('type', AdmissionStatus::WRITTEN_RESULT)->update(['status' => 1]);
            } elseif ($type == ExcelUpload::VIVA_LOCATION) {
                AdmissionStatus::where('user_admission_id', $job->id)->where('type', AdmissionStatus::VIVA_LOCATION)->update(['lat' => $lat, 'long' => $long, 'status' => 1]);
            } elseif ($type == ExcelUpload::VIVA_RESULT) {
                AdmissionStatus::where('user_admission_id', $job->id)->where('type', AdmissionStatus::VIVA_RESULT)->update(['status' => 1]);
            }
        }
    }
}
