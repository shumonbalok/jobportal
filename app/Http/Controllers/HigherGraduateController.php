<?php

namespace App\Http\Controllers;

use App\Http\Requests\HigherGraduateRequest;
use App\Http\Resources\HigherGraduateResource;
use App\Models\HigherGraduate;
use App\Models\User;
use Illuminate\Http\Request;

class HigherGraduateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $higherGraduate = HigherGraduate::paginate(10);
        $higherGraduate->load('users');
        $higherGraduate->load('subject');
        $higherGraduate->load('university');
        $higherGraduate->load('major');
        $higherGraduate->load('passingYear');
        $higherGraduate->load('courseDuration');
        return $this->apiResponseResourceCollection(200, 'All Department', HigherGraduateResource::collection($higherGraduate));
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
    public function store(HigherGraduateRequest $request)
    {
        $higherGraduateValidate =  $request->validated();


        if ($request->user_id) {
            $user = User::findOrFail($request->user_id);
        } else {
            $user = auth()->user();
        }

        $higherGraduateValidate['user_id'] = $user->id;
        $higherGraduateValidate['result_type'] = $request->result_type;

        $dataHigherGraducate = HigherGraduate::where(['user_id' => $user->id , 'type' => $request->type]);
        $hg = $dataHigherGraducate->first();

        if(!$dataHigherGraducate->exists()){
            HigherGraduate::create($higherGraduateValidate);
            if($user->higherGraduates()->count()==1)
                $user->increment('profile_strength',10);

            return response()->json([
                    'message' => 'Higher Graduate create Successfully',
                ],201);
        }else{
            $hg->update($higherGraduateValidate);
            return response()->json([
                'message' => 'Higher Graduate Update Successfully',
            ],201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HigherGraduate  $higherGraduate
     * @return \Illuminate\Http\Response
     */
    public function show(HigherGraduate $higherGraduate)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HigherGraduate  $higherGraduate
     * @return \Illuminate\Http\Response
     */
    public function edit(HigherGraduate $higherGraduate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HigherGraduate  $higherGraduate
     * @return \Illuminate\Http\Response
     */
    public function update(HigherGraduateRequest $request, HigherGraduate $higherGraduate)
    {
        $higherGraduateValidate =  $request->validated();

        if ($request->user_id) {
            $user = User::findOrFail($request->user_id);
        } else {
            $user = auth()->user();
        }
        $higherGraduate->update([
            'user_id' => $user->id,
            'name' => $request->name,
            'subject_id' => $request->subject_id,
            'universities_id' =>$request->universities_id,
            'roll_no' => $request->roll_no,
            'registration_no' => $request->registration_no,
            'result' => $request->result,
            'major_id' => $request->major_id,
            'passing_year_id' => $request->passing_year_id,
            'course_duration_id' => $request->course_duration_id,
        ]);
        return response()->json(
            [
                'profile_strength' => $user->profile_strength,
                'message' => 'Higher Graduate Update Successfully',
            ],
            201
        );

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HigherGraduate  $higherGraduate
     * @return \Illuminate\Http\Response
     */
    public function destroy(HigherGraduate $higherGraduate)
    {
        //
    }
}
