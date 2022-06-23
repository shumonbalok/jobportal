<?php

namespace App\Http\Controllers;

use App\Http\Resources\MajorResource;
use App\Models\Major;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $major = Major::when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->paginate(10);
        return $this->apiResponseResourceCollection(200, 'All Subject', MajorResource::collection($major));
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
        $majorValidate =  $request->validate([
            'name' => 'required|string|unique:subjects,name,',
            'subject_id'=>'required|exists:subjects,id'
        ]);

        $examination = Major::create($majorValidate);
        return $this->apiResponse(201, 'Major create Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function show(Major $major)
    {
        return $this->apiResponseResourceCollection(200, 'All Group', MajorResource::make($major));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function edit(Major $major)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Major $major)
    {
        $majorValidate =  $request->validate([
            'name' => 'required|string|unique:majors,name,' . $major->name ,
            'subject_id'=>'required|exists:subjects,id'
        ]);
        $major->update($majorValidate);
        return $this->apiResponse(201, 'Major update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Major  $major
     * @return \Illuminate\Http\Response
     */
    public function destroy(Major $major)
    {
        $major->delete();
        return $this->apiResponse(201, 'Major Delete Successfully');
    }

    // public function forceDelete($id)
    // {
    //     $major= Major::withTrashed()->find($id);
    //     $major->forceDelete();
    //     return $this->apiResponse(201, 'Major  Delete Successfully');
    // }

    public function majorList()
    {
        $major = Major::all();
        return $this->apiResponseResourceCollection(200, 'All Subject', MajorResource::collection($major));
    }
}
