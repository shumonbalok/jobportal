<?php

namespace App\Http\Controllers;

use App\Http\Resources\SubjectResource;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subject = Subject::when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->paginate(10);
        $subject->load('group');
        $subject->load('majors');
        return $this->apiResponseResourceCollection(200, 'All Board', SubjectResource::collection($subject));

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
        $subjectValidate =  $request->validate([
            'name' => 'required|string|unique:subjects,name,',
            'group_id'=>'required|exists:groups,id'
        ]);

        $examination = Subject::create($subjectValidate);
        return $this->apiResponse(201, 'Subject create Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        $subject->load('majors');
        return $this->apiResponseResourceCollection(200, 'All Group', SubjectResource::make($subject));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $subjectValidate =  $request->validate([
            'name' => 'required|string|unique:subjects,name,' . $subject->id ,
            'group_id'=>'required|exists:groups,id'
        ]);
        $subject->update($subjectValidate);
        return $this->apiResponse(201, 'Subjects update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();
        return $this->apiResponse(201, 'Subjects Delete Successfully');
    }

    // public function forceDelete($id)
    // {
    //     $upazila= Subject::withTrashed()->find($id);
    //     $upazila->forceDelete();
    //     return $this->apiResponse(201, 'Subjects  Delete Successfully');
    // }

    public function subjectsList()
    {
        $subject = Subject::with('majors')->get();
        return $this->apiResponseResourceCollection(200, 'All Board', SubjectResource::collection($subject));

    }
}
