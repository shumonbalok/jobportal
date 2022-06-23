<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserFavouriteDepartmentResource;
use App\Http\Resources\UserFavouriteGradeResource;
use App\Models\Department;
use App\Models\Grade;
use App\Models\UserFavouriteDepartment;
use App\Models\UserFavouriteGrade;
use Illuminate\Http\Request;

class UserFavouriteGradeController extends Controller
{

    public function index()
    {
        $userFavouriteGrade = Grade::all();
        return $this->apiResponseResourceCollection(200, ' User Grade Favourite List', UserFavouriteGradeResource::collection($userFavouriteGrade));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grade_id' => 'required|exists:grades,id'
        ]);
        $userFavouriteGrade =  auth()->user()->userFavouriteGrade();
        if($userFavouriteGrade->count() != null){
            $userFavouriteGrade->delete();
            foreach($request->grade_id as $grade_id) {
                UserFavouriteGrade::create([
                    'user_id'   => auth()->id(),
                    'grade_id' => $grade_id
                ]);
            }
        }else{
            foreach($request->grade_id as $grade_id) {
                UserFavouriteGrade::create([
                    'user_id'   => auth()->id(),
                    'grade_id' => $grade_id
                ]);
            }
        }
        return $this->apiResponse(201, 'Grade Favaourite Successfully');
    }
}
