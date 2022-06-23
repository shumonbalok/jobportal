<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserFavouriteGradeResource;
use App\Http\Resources\UserFavouriteUniversityResource;
use App\Models\Grade;
use App\Models\University;
use App\Models\UserFavouriteDepartment;
use App\Models\UserFavouriteGrade;
use App\Models\UserFavouriteUniversity;
use Illuminate\Http\Request;

class UserFavouriteUniversityController extends Controller
{

    public function index()
    {

        $userFavouriteUniversity = University::all();
        return $this->apiResponseResourceCollection(200, 'User University Favourite List', UserFavouriteUniversityResource::collection($userFavouriteUniversity));
    }

    public function store(Request $request)
    {
        $request->validate([
            'university_id' => 'required|exists:universities,id'
        ]);
        $userFavouriteUniversity = auth()->user()->userFavouriteUniversities();
        if($userFavouriteUniversity->count() != null){
            $userFavouriteUniversity->delete();
            foreach($request->university_id as $university_id) {
                UserFavouriteUniversity::create([
                    'user_id'   => auth()->id(),
                    'university_id' => $university_id
                ]);
            }
        }else{
            foreach($request->university_id as $university_id) {
                UserFavouriteUniversity::create([
                    'user_id'   => auth()->id(),
                    'university_id' => $university_id
                ]);
            }
        }
        return $this->apiResponse(201, 'Universities Favaourite Successfully');
    }
}
