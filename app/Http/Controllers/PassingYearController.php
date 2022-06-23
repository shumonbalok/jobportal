<?php

namespace App\Http\Controllers;

use App\Http\Resources\PassingYearCollection;
use App\Http\Resources\PassingYearResource;
use App\Models\PassingYear;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PassingYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $passingYear = PassingYear::when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->paginate(10);
        return $this->apiResponseResourceCollection(200, 'All Passing Year', PassingYearResource::collection($passingYear));
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
        $passingYearValidate =  $request->validate([
            'name' => 'required|string|min:4|max:4|unique:passing_years,name',
        ]);
        $passingYear = PassingYear::create([
            'name' => Carbon::createFromFormat('Y', $request->name)->format('Y')
        ]);
        return $this->apiResponse(201,  'Passing Year create Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PassingYear  $passingYear
     * @return \Illuminate\Http\Response
     */
    public function show(PassingYear $passingYear)
    {
        return $this->apiResponseResourceCollection(200, 'All Passing Year', PassingYearResource::make($passingYear));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PassingYear  $passingYear
     * @return \Illuminate\Http\Response
     */
    public function edit(PassingYear $passingYear)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PassingYear  $passingYear
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PassingYear $passingYear)
    {
        $examinationValidate =  $request->validate([
            'name' => 'required|string|unique:passing_years,name,' . $passingYear->id ,
        ]);
        $passingYear->update([
            'name' => Carbon::createFromFormat('Y', $request->name)->format('Y')
        ]);
        return $this->apiResponse(201, 'Passing Year update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PassingYear  $passingYear
     * @return \Illuminate\Http\Response
     */
    public function destroy(PassingYear $passingYear)
    {
        $passingYear->delete();
        return $this->apiResponse(201, 'Passing Year Delete Successfully');
    }

    public function passingYearList()
    {
        $passingYear = PassingYear::all();
        return $this->apiResponseResourceCollection(200, 'All Passing Year', PassingYearResource::collection($passingYear));
    }
}
