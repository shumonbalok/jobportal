<?php

namespace App\Http\Controllers;

use App\Http\Resources\NonAppliedResource;
use App\Imports\ExcelUploadImport;
use App\Models\ExcelUpload;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class   ExcelUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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

        $request->validate([
            'admission_id'       => 'required|exists:admissions,id',
            'excel_upload' => 'required|mimes:xlsx, xls',
            'type'  => 'required'
        ]);
        // dd($request);
        $excel =  Excel::import(new ExcelUploadImport($request->admission_id, $request->type), request()->file('excel_upload'));
        return $this->apiResponse(201, 'excel_upload Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExcelUpload  $excelUpload
     * @return \Illuminate\Http\Response
     */
    public function show(ExcelUpload $excelUpload)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExcelUpload  $excelUpload
     * @return \Illuminate\Http\Response
     */
    public function edit(ExcelUpload $excelUpload)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExcelUpload  $excelUpload
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExcelUpload $excelUpload)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExcelUpload  $excelUpload
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExcelUpload $excelUpload)
    {
        //
    }

    public function check(Request $request){
        $ExcelUpload = ExcelUpload::where('admission_id', $request->admission_id)->where('type', $request->type)->where('roll_number',$request->search )->first();
        if($ExcelUpload)
        return $this->apiResponse(200,'Search Result',NonAppliedResource::make($ExcelUpload));
        else
        return $this->apiResponse(404,'Sorry No Result Found');
    }
}
