<?php

namespace App\Http\Controllers;

use App\Http\Resources\CourseDurationResource;
use App\Http\Resources\UserFavouriteDepartmentResource;
use App\Models\Department;
use App\Models\UserFavouriteDepartment;
use App\Models\UserFavouriteGrade;
use Illuminate\Http\Request;

class UserFavouriteDepartmentController extends Controller
{

    public function index()
    {
        $userFavouriteDpearment = Department::all();
        return $this->apiResponseResourceCollection(200, 'User Department Favourite List', UserFavouriteDepartmentResource::collection($userFavouriteDpearment));

    }

    public function store(Request $request)
    {

       $request->validate([
            'department_id' => 'required|exists:departments,id'
        ]);

        $userFavouriteDepartment = auth()->user()->userFavouriteJobs();
        if($userFavouriteDepartment->count() != null){
            $userFavouriteDepartment->delete();
            foreach($request->department_id as $department_id) {
                UserFavouriteDepartment::create([
                    'user_id'   => auth()->id(),
                    'department_id' => $department_id
                ]);
            }
        }else{
            foreach($request->department_id as $department_id) {
                UserFavouriteDepartment::create([
                    'user_id'   => auth()->id(),
                    'department_id' => $department_id
                ]);
            }
        }

        return $this->apiResponse(201, 'Department Favaourite Successfully');
    }
}
