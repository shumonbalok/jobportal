<?php

namespace App\Http\Controllers\Admission;

use App\Http\Controllers\Controller;
use App\Http\Resources\UniteResource;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
   public function index()
   {
       $units=Unit::when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->paginate(10);
     //  dd($units);
       return $this->apiResponseResourceCollection(200,'Available Units List',UniteResource::collection($units));
   }
   public function store(Request $request)
   {
       $input=$request->validate([
           'name' =>'required|unique:units,name'
       ]);

       Unit::create($input);
       return $this->apiResponse(200,'Unit Created Successfully');
   }
   public function update(Request $request,Unit $unit)
   {
       $input=$request->validate([
           'name' =>'required|unique:units,name,'.$unit->name
       ]);
       $unit->update($input);
        return $this->apiResponse(200,'Unit Updated Successfully');
   }
   public function destroy(Unit $unit)
   {
       $unit->delete();
        return $this->apiResponse(200,'Unit Deleted Successfully');
   }
    // public function forceDelete($id)
    // {
    //     $unit= Unit::withTrashed()->find($id);
    //     $unit->forceDelete();
    //     return $this->apiResponse(201, 'Unit  Delete Successfully');
    // }

    public function unitList()
    {
        $units=Unit::all();
        return $this->apiResponseResourceCollection(200,'Available Units List',UniteResource::collection($units));
    }
}
