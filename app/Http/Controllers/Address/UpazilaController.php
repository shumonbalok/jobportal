<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use App\Http\Resources\UpazilaResource;
use App\Models\Upazila;
use Illuminate\Http\Request;

class UpazilaController extends Controller
{
     public function index()
    {
        $upazilas =Upazila::with('postOffice')->when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->paginate(10);
        // $upazilas->load('postOffice');
        return $this->apiResponseResourceCollection(201, 'Upazila List', UpazilaResource::collection($upazilas));
    }

    public function store(Request $request)
    {
        $input=$request->validate([
            'name' =>'required|string',
            'district_id'=>'required|exists:districts,id'
        ]);
        Upazila::create($input);
         return $this->apiResponse(201, 'Upazila create Successfully');
    }
    public function show(Upazila $upazila)
    {
        $upazila->load('postOffice');
        return $this->apiResponseResourceCollection(201, 'Upazila Info', UpazilaResource::make($upazila));
    }
     public function update(Request $request,Upazila $upazila)
    {
        $input=$request->validate([
            'name' =>'required|string',
            'district_id'=>'required|exists:districts,id'
        ]);
        $upazila->update($input);
         return $this->apiResponse(201, 'Upazila Update Successfully');
    }

    public function destroy(Upazila $upazila)
    {
        $upazila->delete();
        return $this->apiResponse(201, 'Upazila Deleted Successfully');
    }
    //  public function forceDelete($id)
    // {
    //     $upazila= Upazila::withTrashed()->find($id);
    //     $upazila->forceDelete();
    //     return $this->apiResponse(201, 'Upazila  Delete Successfully');
    // }

    public function upazilaList()
    {
        $upazilas =Upazila::all();
        return $this->apiResponseResourceCollection(201, 'Upazila List', UpazilaResource::collection($upazilas));
    }
}
