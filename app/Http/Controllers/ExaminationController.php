<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExaminationCollection;
use App\Http\Resources\ExaminationResource;
use App\Models\Examination;
use Illuminate\Http\Request;

class ExaminationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $examination = Examination::when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->paginate(10);
        return $this->apiResponseResourceCollection(200, 'All Examination', ExaminationResource::collection($examination));
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
       $examinationValidate =  $request->validate([
            'name' => 'required|string|unique:examinations,name,',
        ]);

        $examination = Examination::create($examinationValidate);
        return $this->apiResponse(201, 'Examination create Successfully');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Examination  $examination
     * @return \Illuminate\Http\Response
     */
    public function show(Examination $examination)
    {
        return $this->apiResponseResourceCollection(200, 'All Examination', ExaminationResource::make($examination));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Examination  $examination
     * @return \Illuminate\Http\Response
     */
    public function edit(Examination $examination)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Examination  $examination
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Examination $examination)
    {

        $examinationValidate =  $request->validate([
            'name' => 'required|string|unique:examinations,name,' . $examination->name ,
        ]);
        $examination->update($examinationValidate);
        return $this->apiResponse(201, 'Examination update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Examination  $examination
     * @return \Illuminate\Http\Response
     */
    public function destroy(Examination $examination)
    {
        $examination->delete();
        return $this->apiResponse(201, 'Examination Delete Successfully');
    }

    // public function forceDelete($id)
    // {
    //     $examination= Examination::withTrashed()->find($id);
    //     $examination->forceDelete();
    //     return $this->apiResponse(201, 'Examination  Delete Successfully');
    // }

    public function examinationList()
    {
        $examination = Examination::all();
        return $this->apiResponseResourceCollection(200, 'All Examination', ExaminationResource::collection($examination));
    }
}
