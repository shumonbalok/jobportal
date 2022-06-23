<?php

namespace App\Http\Controllers\Admission;

use App\Http\Controllers\Controller;
use App\Http\Requests\UniversityRequest;
use App\Http\Resources\UniversityResource;
use App\Models\University;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $universities=University::when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->paginate(10);
        return $this->apiResponseResourceCollection(201, 'University list', UniversityResource::collection($universities));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UniversityRequest $request)
    {
        $input=$request->validated();


        if ($request->hasFile('logo')) {
            $fileName = Rand() . '.' . $request->file('logo')->getClientOriginalExtension();

            $logo = $request->file('logo')->storeAs('university', $fileName, 'public');
            $input['logo']=$logo;
        }



        University::create($input);
       return response()->json([

            'message' => 'University successfully created',
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function show(University $university)
    {

        return $this->apiResponseResourceCollection(200, ' University', UniversityResource::make($university));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, University $university)
    {

        if($request->status!=null)
        {
            $university->update(['status'=>$request->status]);
            return $this->apiResponse(201, 'Update University Status Successfully',);
        }

        else
        {
       $input= $request->validate([
           'name' => 'unique:universities,name,'.$university->id.',id',
           'location'=>'required|string'
        ]);
        if($request->logo)
        {
            $input['logo']=uploadFile($request->file('logo','university'));
        }
        $university->update($input);
         return $this->apiResponse(201, 'Update University Successfully', );
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\University  $university
     * @return \Illuminate\Http\Response
     */
    public function destroy(University $university)
    {
        $university->delete();
        return $this->apiResponse(200,'University Deleted Successfully');
    }
    //  public function forceDelete($id)
    // {
    //     $university= University::withTrashed()->find($id);
    //     $university->forceDelete();
    //     return $this->apiResponse(201, 'University  Delete Successfully');
    // }
    public function universityList()
    {

        $universities=University::all();
        return $this->apiResponseResourceCollection(201, 'University list', UniversityResource::collection($universities));
    }


}
