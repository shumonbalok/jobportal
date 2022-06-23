<?php

namespace App\Http\Controllers;

use App\Http\Requests\GraduateRequest;
use App\Http\Resources\GraduateResource;
use App\Models\Grade;
use App\Models\Graduate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GraduateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $graduate = Graduate::where('user_id',auth()->id())->paginate(10);
       // $graduate->load('users');
        $graduate->load('examination');
        $graduate->load('borad');
        $graduate->load('passingYear');
        $graduate->load('group');
        return $this->apiResponseResourceCollection(200, 'All Department', GraduateResource::collection($graduate));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GraduateRequest $request)
    {
        $graduateValidate =  $request->validated();



        if ($request->user_id) {
            $user = User::findOrFail($request->user_id);
        } else {
            $user = auth()->user();
        }

        $graduateValidate['user_id'] = $user->id;
        $graduateValidate['result_type'] = $request->result_type;
        $graduateCheck = Graduate::where('user_id',$user->id)->where('examination_id',$request->examination_id);
        $g = $graduateCheck->first();

        if(!$graduateCheck->exists()){
            Graduate::create($graduateValidate);

            if($user->graduates()->count()==1) $user->increment('profile_strength',10);
            return response()->json(
                [
                    'profile_strength' => $user->profile_strength,
                    'message' => 'Graduate Update  Successfully',
                ],
                201
            );
        }else{
            $g->update($graduateValidate);
            return response()->json(
                [
                    'message' => 'Graduate Update  Successfully',
                ],
                201
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Graduate  $graduate
     * @return \Illuminate\Http\Response
     */
    public function show(Graduate $graduate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Graduate  $graduate
     * @return \Illuminate\Http\Response
     */
    public function edit(Graduate $graduate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Graduate  $graduate
     * @return \Illuminate\Http\Response
     */
    public function update(GraduateRequest $request, Graduate $graduate)
    {

        $graduateValidate =  $request->validated();

        $graduate->update([
            'name' => $request->name,
            'examination_id' => $request->examination_id,
            'board_id' =>$request->board_id,
            'roll_no' => $request->roll_no,
            'registration_no' => $request->registration_no,
            'result' => $request->result,
            'passing_year_id' => $request->passing_year_id,
            'group_id' => $request->group_id,
        ]);
        return $this->apiResponse(201, 'Graduate Update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Graduate  $graduate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Graduate $graduate)
    {

    }
}
