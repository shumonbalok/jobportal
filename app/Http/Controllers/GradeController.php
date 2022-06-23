<?php

namespace App\Http\Controllers;

use App\Http\Resources\GradeResource;
use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $grade = Grade::paginate(10);
        return $this->apiResponseResourceCollection(200, 'All Grade', GradeResource::collection($grade));
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
       $grade =  $request->validate([
            'name' => 'required|string|unique:grades,name,',
        ]);

        $grade = Grade::create($grade);
        return $this->apiResponse(201, 'Grade create Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function show(Grade $grade)
    {
        return $this->apiResponseResourceCollection(200, 'All Grade', GradeResource::make($grade));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function edit(Grade $grade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Grade $grade)
    {


       $grades =  $request->validate([
            'name' => 'required|string|unique:grades,name,' . $grade->name ,
        ]);
        $grade->update($grades);
        return $this->apiResponse(201, 'Grade update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();
        return $this->apiResponse(201, 'Grade Delete Successfully');
    }

    // public function forceDelete($id)
    // {
    //     $grade= Grade::withTrashed()->find($id);
    //     $grade->forceDelete();
    //     return $this->apiResponse(201, 'Grade  Delete Successfully');
    // }

    public function gradeList()
    {
        $grade = Grade::when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->get();
        return $this->apiResponseResourceCollection(200, 'All Grade', GradeResource::collection($grade));
    }
}

