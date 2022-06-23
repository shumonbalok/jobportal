<?php

namespace App\Http\Controllers;

use App\Http\Resources\MerchantResource;
use App\Http\Resources\UserResorce;
use App\Models\AppliedJob;
use App\Models\User;
use App\Notifications\SuccesfullNotification;
use App\Models\UserAdmission;
use Illuminate\Http\Request;

class TransferController extends Controller
{

    public function merchantUser($id){

        $appliedJob = AppliedJob::findOrFail($id)->users();
        $usersId = $appliedJob->first();

        if($appliedJob->exists() == null){
            $users = User::where('type' , 0)->whereKeyNot(1)->get();

        }else{
            $users = User::where('id',$usersId->id)->get();
        }
        return $this->apiResponseResourceCollection(200, 'Search Result', MerchantResource::collection($users));
    }

    public function jobTransferByMerchant(Request $request ,$id){
        $appliedJob = AppliedJob::findOrFail($id);
        $transferJob = $appliedJob->users();
        $user = User::findOrFail($request->merchant_id);
        if($transferJob->exists() == null){
             $appliedJob->users()->attach($request->merchant_id);
        }else{
             $appliedJob->users()->sync($request->merchant_id);
        }
         $appliedJob->update([
            'send_by_status' => 1
        ]);
        
        $Successfully = [
            'title' => 'Transfer  Succsfully',
            'message' => "Transfer  Succsfully",
            'url' => '/job/merchent/apply'
            
        ];
        $user->notify(new SuccesfullNotification($Successfully));
        return $this->apiResponse(201, 'Job create Successfully');
    }

    public function jobNoTransfer($id){
        $appliedJobStatusUpdate = AppliedJob::where('id', $id)->update([
            'status' => 1
        ]);
        return $this->apiResponse(201, 'Status Update Successfully');

    }

    public function admissionMerchantUser($id){

        $userAdmission = AppliedJob::findOrFail($id)->users();
        $usersId = $userAdmission->first();

        if($userAdmission->exists() == null){
            $users = User::where('type' , 0)->whereKeyNot(1)->get();

        }else{
            $users = User::where('id',$usersId->id)->get();
        }
        return $this->apiResponseResourceCollection(200, 'Search Result', MerchantResource::collection($users));
    }

    public function admissionTransferByMerchant(Request $request ,$id){
        $userAdmission = UserAdmission::findOrFail($id);
        $transferAdmission = $userAdmission->users();

        if($transferAdmission->exists() == null){
             $userAdmission->users()->attach($request->merchant_id);
        }else{
             $userAdmission->users()->sync($request->merchant_id);
        }
        
        $userAdmission->update([
            'send_by_status' => 1
        ]);

        return $this->apiResponse(201, 'Admission create Successfully');
    }

    public function admissionNoTransfer($id){
        $appliedJobStatusUpdate = UserAdmission::where('id', $id)->update([
            'status' => 1
        ]);
        return $this->apiResponse(201, 'Status Update Successfully');

    }
}
