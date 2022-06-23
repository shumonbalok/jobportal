<?php

namespace App\Http\Controllers;

use App\Http\Resources\GroupCollection;
use App\Http\Resources\GroupResource;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $group = Group::when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->paginate(10);
        return $this->apiResponseResourceCollection(200, 'All Group', GroupResource::collection($group));
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
        $GroupValidate =  $request->validate([
            'name' => 'required|string|unique:groups,name,',
        ]);

        $examination = Group::create($GroupValidate);
        return $this->apiResponse(201, 'Group create Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        return $this->apiResponseResourceCollection(200, 'All Group', GroupResource::make($group));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $groupValidate =  $request->validate([
            'name' => 'required|string|unique:groups,name,' . $group->name ,
        ]);
        $group->update($groupValidate);
        return $this->apiResponse(201, 'Group update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {

        $group->delete();
        return $this->apiResponse(201, 'Group Delete Successfully');


    }

    // public function forceDelete($id)
    // {
    //     $group= Group::withTrashed()->find($id);
    //     $group->forceDelete();
    //     return $this->apiResponse(201, 'Group  Delete Successfully');
    // }
    public function groupList()
    {
        $group = Group::all();
        return $this->apiResponseResourceCollection(200, 'All Group', GroupResource::collection($group));
    }

}
