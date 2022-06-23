<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $department = Department::when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->paginate();

        return $this->apiResponseResourceCollection(200, 'All Department', DepartmentResource::collection($department));
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
      
        $department =  $request->validate([
            'name' => 'required|string|unique:departments,name,',
        ]);


        if ($request->hasFile('image')) {
            $fileName = Rand() . '.' . $request->file('image')->getClientOriginalExtension();

            $image = $request->file('image')->storeAs('university', $fileName, 'public');
            $department['image']= $image;
        }
        $department = Department::create($department);
        return $this->apiResponse(201, 'Department created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        return $this->apiResponseResourceCollection(200, 'All Department', DepartmentResource::make($department));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $departments =  $request->validate([
            'name' => 'required|string|unique:departments,name,' . $department->id ,
        ]);
        $image = $department->image;
        if ($request->hasFile('image')) {
            $fileName = Rand() . '.' . $request->file('image')->getClientOriginalExtension();
            $image = $request->file('image')->storeAs('university', $fileName, 'public');
        }
        $departments['image']= $image;
        $department->update($departments);
        return $this->apiResponse(201, 'Department updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return $this->apiResponse(201, 'Department Delete Successfully');
    }

    // public function forceDelete($id)
    // {
    //     $department= Department::withTrashed()->find($id);
    //     $department->forceDelete();
    //     return $this->apiResponse(201, 'Department  Delete Successfully');
    // }

    public function departmentsList()
    {
        $department = Department::all();
        return $this->apiResponseResourceCollection(200, 'All Department', DepartmentResource::collection($department));
    }
}
