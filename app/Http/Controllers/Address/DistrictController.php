<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use App\Http\Resources\DistrictResource;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index()
    {
        $districts =District::with('upazila.postOffice')->when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->paginate(10);
        // $districts->load('upazila.postOffice');
        return $this->apiResponseResourceCollection(201, 'Districts List', DistrictResource::collection($districts));
    }

    public function store(Request $request)
    {
        $input=$request->validate([
            'name' =>'required|string'
        ]);
        District::create($input);
         return $this->apiResponse(201, 'District create Successfully');
    }
    public function show(District $district)
    {
        $district->load('upazila.postOffice');
         return $this->apiResponseResourceCollection(201, 'District Info', DistrictResource::make($district));
    }
    public function update(Request $request,District $district)
    {
        $input=$request->validate([
            'name' =>'required|unique:districts,name,'.$district->name
        ]);
        $district->update($input);
         return $this->apiResponse(201, 'District Updated Successfully');
    }
    public function destroy(District $district)
    {
       $district->delete();
        return $this->apiResponse(201, 'District Deleted Successfully');
    }
    //  public function forceDelete($id)
    // {
    //     $district= District::withTrashed()->find($id);
    //     $district->forceDelete();
    //     return $this->apiResponse(201, 'District  Delete Successfully');
    // }

    public function districtList()
    {
        $districts =District::with('upazila.postOffice')->get();
        return $this->apiResponseResourceCollection(201, 'Districts List', DistrictResource::collection($districts));
    }
}
