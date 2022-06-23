<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\GroupResource;
use App\Http\Resources\UserResorce;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        dd('ok');
        return view('website.index');
    }
    public  function userList(){
        $user = User::with(['basicInfo.quota', 'address.district', 'address.upazila',  'address.postOffice', 'experience', 'skill', 'graduates.examination', 'higherGraduates.subject','higherGraduates.major', 'allphoto', 'allphotosub'])->when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->whereKeyNot(1)->paginate(10);
        // dd($user);
        return $this->apiResponseResourceCollection(200, 'All User', UserResorce::collection($user));
    }

    public function userShow($id){

        $user = User::findOrFail($id);


        return $this->apiResponseResourceCollection(200, 'All User', UserResorce::make($user));
    }
}
