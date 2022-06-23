<?php

namespace App\Http\Controllers\Admission;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdmissionResource;
use App\Http\Resources\AdmissionStatusResource;
use App\Models\Admission;
use App\Models\AdmissionStatus;
use App\Models\ExcelUpload;
use Illuminate\Http\Request;

class AdmissionStatusController extends Controller
{

   public function store(Request $request,$id)
   {
      $input= $request->validate([
           'status_name'    =>'required|string',
           'details'        =>'nullable|string'
       ]);

       $input['user_admission_id']=$id;

       AdmissionStatus::create($input);
       return $this->apiResponse(200,'Status Created For User Admission');
   }
   public function update(Request $request , AdmissionStatus $admissionStatus)
   {
     $request->validate([
           'file'    =>'nullable',
          
       ]);
        $file = uploadFile($request->file('file'), 'appliedJob');
      $admissionStatus->update(['file'=>$file]);
       return $this->apiResponse(200,'Status Updated For User Admission');
   }
   public function statusList($id)
   {
       $status=AdmissionStatus::where('user_admission_id',$id)->get();
       return $this->apiResponseResourceCollection(200,'Admission Status' ,AdmissionStatusResource::collection($status));
   }
    //    public function forceDelete($id)
    //     {
    //         $admissionStatus= AdmissionStatus::withTrashed()->find($id);
    //         $admissionStatus->forceDelete();
    //         return $this->apiResponse(201, 'Admission Status  Deleted Permanently');
    //     }




    ///non applied Admission Status

    public function index()
    {
        $applied = auth()->user()->userAdmission;
        $admissions =  Admission::all();

        foreach ($admissions as $key => $admission) {
            $admission->status = $this->status($admission);

            foreach ($applied as $appliedAdmission) {
                if ($admission->id == $appliedAdmission->admission_id) {
                    $admissions = $admissions->forget($key);
                }
            }
        }

        return $this->apiResponseResourceCollection(200, 'Non Applied Admission list', AdmissionResource::collection($admissions));
    }

    public function status($admission)
    {
        $status  = [
            [
                'id' => 1,
                'description' => null,
                'short_name' => 'Preli',
                'lat' => null,
                'long'  => null,
                'file' => null,
                'active' => ExcelUpload::where([['admission_id', $admission->id], ['type', 1]])->first() !=  null ? 1 : 0,
                'type'   => 1
            ],
            [
                'id' => 2,
                'description' => null,
                'short_name' => 'Preli',
                'lat' => null,
                'long'  => null,
                'file' => null,
                'active' => ExcelUpload::where([['admission_id', $admission->id], ['type', 2]])->first() !=  null ? 1 : 0,
                'type'   => 2
            ],
            [
                'id' => 3,
                'description' => null,
                'short_name' => 'Written',
                'lat' => null,
                'long'  => null,
                'file' => null,
                'active' => ExcelUpload::where([['admission_id', $admission->id], ['type', 3]])->first() !=  null ? 1 : 0,
                'type'   => 3
            ],
            [
                'id' => 4,
                'description' => null,
                'short_name' => 'Written',
                'lat' => null,
                'long'  => null,
                'file' => null,
                'active' => ExcelUpload::where([['admission_id', $admission->id], ['type', 4]])->first() !=  null ? 1 : 0,
                'type'   => 4
            ],
            [
                'id' => 5,
                'description' => null,
                'short_name' => 'Viva',
                'lat' => null,
                'long'  => null,
                'file' => null,
                'active' => ExcelUpload::where([['admission_id', $admission->id], ['type', 5]])->first() !=  null ? 1 : 0,
                'type'   => 5
            ],
            [
                'id' => 6,
                'description' => null,
                'short_name' => 'Viva',
                'lat' => null,
                'long'  => null,
                'file' => null,
                'active' => ExcelUpload::where([['admission_id', $admission->id], ['type', 6]])->first() !=  null ? 1 : 0,
                'type'   => 6
            ],
        ];
        return $status;
    }
}
