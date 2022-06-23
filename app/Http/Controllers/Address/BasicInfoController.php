<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use App\Http\Requests\BasicInfoRequest;
use App\Http\Resources\BasicInfoResource;
use App\Models\BasicInfo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BasicInfoController extends Controller
{
    public function store(BasicInfoRequest $request)
    {
        if($request->user_id)
        {
            $user=User::findOrfail($request->user_id);
        }
        else
        $user=auth()->user();
        $input=$request->validated();
        $input['user_id']=$user->id;
        // $input['passport_number']=$request->passport_number;
        $input['birth_date']=Carbon::parse($request->birth_date)->format('Y-m-d');

        $user->update(['name'=>$request->full_name]);
        if($user->basicInfo==null){
            BasicInfo::create($input);
             $user->increment('profile_strength',20);
        }
        else $user->basicInfo->update($input);

        return response()->json([
            'profile_strength' => $user->profile_strength,
            'message' => 'Basic Info Saved',
        ], 201);
    }

    public function show(BasicInfo $basicInfo)
    {
         return $this->apiResponse(200, 'Basic Info ', BasicInfoResource::make($basicInfo));
    }

    public function update(BasicInfoRequest $request,$id)
    {
        $input=$request->validated();
        $input['birth_date']=Carbon::parse($request->birth_date)->format('Y-m-d');
        $input['passport_number']=$request->passport_number;
        $basicInfo=BasicInfo::find($id);
        $user=User::findOrfail($basicInfo->user_id);
        $user->update(['name' => $request->full_name]);
        $basicInfo->update($input);
        return response()->json([
            'profile_strength' => $user->profile_strength,
            'message' => 'Basic Info Updated',
        ], 201);
    }
    //  public function forceDelete($id)
    // {
    //     $basicInfo= BasicInfo::withTrashed()->find($id);
    //     $basicInfo->forceDelete();
    //     return $this->apiResponse(201, 'Basic Info  Delete Successfully');
    // }
}
