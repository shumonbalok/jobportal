<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseDurationResource;
use App\Models\CourseDuration;
use Illuminate\Http\Request;

class CourseDurationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $CourseDuration  = CourseDuration::when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->paginate(10);
        return $this->apiResponseResourceCollection(200, 'All Available Course Duration List', CourseDurationResource::collection($CourseDuration));
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
    public function store(Request $request)
    {
        $courseDurationValidate =  $request->validate([
            'name' => 'required|string|unique:course_durations,name,',
        ]);

        $courseDuration = CourseDuration::create($courseDurationValidate);
        return $this->apiResponse(201, 'courseDuration create Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CourseDuration  $courseDuration
     * @return \Illuminate\Http\Response
     */
    public function show(CourseDuration $courseDuration)
    {
        return $this->apiResponseResourceCollection(200, 'Course Duration Details', CourseDurationResource::make($courseDuration));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CourseDuration  $courseDuration
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseDuration $courseDuration)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CourseDuration  $courseDuration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseDuration $courseDuration)
    {
        $courseDurationValidate =  $request->validate([
            'name' => 'required|string|unique:course_durations,name,' . $courseDuration->name ,

        ]);
        $courseDuration->update($courseDurationValidate);
        return $this->apiResponse(201, 'CourseDuration update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CourseDuration  $courseDuration
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseDuration $courseDuration)
    {
        $courseDuration->delete();
        return $this->apiResponse(201, 'Course Duration Delete Successfully');
    }

    // public function forceDelete($id)
    // {
    //     $upazila= CourseDuration::withTrashed()->find($id);
    //     $upazila->forceDelete();
    //     return $this->apiResponse(201, 'Course Duration  Delete Successfully');
    // }
    public function courseDurationList()
    {
        $CourseDuration  = CourseDuration::all();
        return $this->apiResponseResourceCollection(200, 'All Available Course Duration List', CourseDurationResource::collection($CourseDuration));
    }

}
