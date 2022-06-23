<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use App\Http\Resources\SkillResource;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $skills=Skill::where('user_id',auth()->user()->id)->get();
             return $this->apiResponseResourceCollection(200, 'Skill Details List', SkillResource::collection($skills));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $data=  $request->all();
    


        foreach($data['skill'] as $key=>$value){
            // return $value;

            $user_id  = (array_key_exists('user_id',$value)) ? $value['user_id'] : auth()->id();
            $user= User::findOrfail($user_id);
            $id  = (array_key_exists('id',$value)) ? $value['id'] : null;
            $skillCheck = Skill::where(['user_id' => $user_id])->where('id' ,  $id);
            $skill = $skillCheck->first();
            if(!$skillCheck->exists()){
            Skill::create([
                'name'              =>$value['name'],
                'institute_name'    =>$value['institute_name'],
                'duration'          =>$value['duration'],
                'result'            =>$value['result'],
                'user_id'           =>$user_id,
            ]);
            if($user->skill()->count()==1) {
                $user->increment('profile_strength',5);
            }
        }else{
            $skill->update([
                'name'              =>$value['name'],
                'institute_name'    =>$value['institute_name'],
                'duration'          =>$value['duration'],
                'result'            =>$value['result'],
                'user_id'           =>$user_id,
        ]);
        }
        }

        return response()->json(
            [
                'profile_strength' => $user->profile_strength,
                'message' => 'Skill Create &  Updated Successfully',
            ],
            201
        );

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Skil  $skil
     * @return \Illuminate\Http\Response
     */
    public function show(Skill $skill)
    {

         return $this->apiResponseResourceCollection(200, 'Skill Details List', SkillResource::make($skill));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Skil  $skil
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Skil  $skil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Skill $skill)
    {
         if($request->user_id) $user=User::findOrfail($request->user_id);
        else $user=auth()->user();
        $input=$request->validate([
            'name'              =>'required|string',
            'institute_name'    =>'required|string',
            'duration'          =>'required|string',
            'result'            =>'required|string'
        ]);
        $input['user_id']=$user->id;
        $skill->update($input);
        return response()->json(
            [
                'profile_strength' => $user->profile_strength,
                'message' => 'Skill create Successfully',
            ],
            201
        );
        return $this->apiResponse(201, 'Skill Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Skil  $skil
     * @return \Illuminate\Http\Response
     */
    public function destroy(Skill $skill)
    {
        $skill->delete();
         return $this->apiResponse(201, 'Skill Deleted Successfully');
    }
    //  public function forceDelete($id)
    // {
    //     $skill= Skill::withTrashed()->find($id);
    //     $skill->forceDelete();
    //     return $this->apiResponse(201, 'Skill  Delete Successfully');
    // }
}
