<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostOfficeResource;
use App\Models\PostOffice;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\PostDec;

class PostOfficeController extends Controller
{
    public function index()
    {
        $postOffice=PostOffice::paginate(10);
         return $this->apiResponseResourceCollection(201, 'Post Office List', PostOfficeResource::collection($postOffice));
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'name' =>'required|string',
            'upazila_id'=>'required|exists:upazilas,id'
        ]);
        PostOffice::create($input);
        return $this->apiResponse(201, 'Post Office created Successfully');
    }
     public function update(Request $request,PostOffice $postOffice)
    {
        $input = $request->validate([
            'name' =>'required|unique:post_offices,name,'.$postOffice->name,
            'upazila_id'=>'required|exists:upazilas,id'
        ]);
        $postOffice->update($input);
        return $this->apiResponse(201, 'Post Office Updated Successfully');
    }
    public function destroy(PostOffice $postOffice)
    {
        $postOffice->delete();
        return $this->apiResponse(201, 'Post Office Deleted Successfully');
    }
    //  public function forceDelete($id)
    // {
    //     $postOffice= PostOffice::withTrashed()->find($id);
    //     $postOffice->forceDelete();
    //     return $this->apiResponse(201, 'Post Office  Delete Successfully');
    // }
    public function postOfficeList()
    {
        $postOffice=PostOffice::with('upazila')->when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->get();
        return $this->apiResponseResourceCollection(201, 'Post Office List', PostOfficeResource::collection($postOffice));
    }

}
