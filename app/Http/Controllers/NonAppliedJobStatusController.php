<?php

namespace App\Http\Controllers;

use App\Http\Resources\NonAppliedJobStatusResource;
use App\Http\Resources\NonAppliedResource;
use App\Imports\ExcelUploadImport;
use App\Imports\JobImport;
use App\Models\ExcelUpload;
use App\Models\Job;
use App\Models\NonAppliedJob;
use App\Models\NonAppliedJobStatus;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class NonAppliedJobStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


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

            $request->validate([
                'job_id'       => 'required|exists:jobs,id',
                'excel_upload' => 'required|mimes:xlsx, xls',
                'type'  =>'required'
            ]);
           // dd($request);
            $excel =  Excel::import(new JobImport($request->job_id,$request->type), request()->file('excel_upload'));
            return $this->apiResponse(201, 'excel_upload Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NonAppliedJobStatus  $nonAppliedJobStatus
     * @return \Illuminate\Http\Response
     */
    public function show(NonAppliedJobStatus $nonAppliedJobStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NonAppliedJobStatus  $nonAppliedJobStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(NonAppliedJobStatus $nonAppliedJobStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NonAppliedJobStatus  $nonAppliedJobStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NonAppliedJobStatus $nonAppliedJobStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NonAppliedJobStatus  $nonAppliedJobStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(NonAppliedJobStatus $nonAppliedJobStatus)
    {
        //
    }

    public function list(){
        $ExcelUpload = Job::with('nonAppliedJobStatus')->get();
        return response()->json([
            'data' => $ExcelUpload,
        ], 201);

    }

    public function check(Request $request)
    {
        $ExcelUpload = NonAppliedJobStatus::where('job_id' , $request->job_id)->where('type' , $request->type)->where('roll_number', $request->search)->first();

        if ($ExcelUpload)
        return $this->apiResponse(200, 'Search Result', NonAppliedJobStatusResource::make($ExcelUpload));
        else
        return $this->apiResponse(404, 'Sorry No Result Found');
    }

}
