<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExperienceResource;
use App\Models\Experience;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

        public function index()
        {
            $experiences=Experience::where('user_id',auth()->user()->id)->get();
             return $this->apiResponseResourceCollection(200, 'Experience Details List', ExperienceResource::collection($experiences));
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
          if($request->user_id)
        {
            $user=User::findOrfail($request->user_id);
        }
        else{
            $user=auth()->user();
        }

        $experienceCheck = Experience::where('user_id' , $user->id)->where('id', $request->id);
        $experience = $experienceCheck->first();


        $input=$request->validate([

            'type'              =>'required',
            'company_name'      =>'required|string',
            'location'          =>'required|string',
            'designation'       =>'required|string',
            'salary'            =>'required|string',
            'currently_working' =>'required',
            'start_date'        =>'required',
            'end_date'          =>'nullable',
        ]);
         $input['user_id']=$user->id;
         $input['start_date']=Carbon::parse($request->start_date)->format('Y-m-d');
         !$input['end_date']?'null':$input['end_date']=Carbon::parse($request->start_date)->format('Y-m-d');

        Experience::create($input);
        if ($user->experience()->count() == 1) $user->increment('profile_strength', 5);
        return response()->json(
            [
                'profile_strength' => $user->profile_strength,
                'message' => 'Experience create Successfully',
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Experience  $experience
     * @return \Illuminate\Http\Response
     */
    public function show(Experience $experience)
    {
       return $this->apiResponseResourceCollection(200, 'Experience Details', ExperienceResource::make($experience));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Experience  $experience
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Experience  $experience
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Experience $experience)
    {
        if($request->user_id) $user=User::findOrfail($request->user_id);
        else $user=auth()->user();


         $input=$request->validate([

            'type'              =>'required',
            'company_name'      =>'required|string',
            'location'          =>'required|string',
            'designation'       =>'required|string',
            'salary'            =>'required|string',
            'currently_working' =>'required',
            'start_date'        =>'required',
            'end_date'          =>'nullable',
        ]);
        $input['user_id']=$user->id;
        $input['start_date']=Carbon::parse($request->start_date)->format('Y-m-d');
         !$input['end_date']?'null':$input['end_date']=Carbon::parse($request->start_date)->format('Y-m-d');
        $experience->update($input);

        return response()->json(
            [
                'profile_strength' => $user->profile_strength,
                'message' => 'Experience Updated Successfully',
            ],
            201
        );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Experience  $experience
     * @return \Illuminate\Http\Response
     */
    public function destroy(Experience $experience)
    {
        $experience->delete();
         return $this->apiResponse(201, 'Experience Deleted Successfully');
    }
    //  public function forceDelete($id)
    // {
    //     $experience= Experience::withTrashed()->find($id);
    //     $experience->forceDelete();
    //     return $this->apiResponse(201, 'Experience  Delete Successfully');
    // }
}
