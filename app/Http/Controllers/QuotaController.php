<?php

namespace App\Http\Controllers;

use App\Http\Resources\QuotaResource;
use App\Models\Quota;
use Illuminate\Http\Request;

class QuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quota = Quota::when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->paginate(10);
        return $this->apiResponseResourceCollection(200, 'All Quota', QuotaResource::collection($quota));
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
        $quota = $request->validate([
            'name' => 'required|string|unique:quotas,name,',
        ]);

        Quota::create($quota);
        return $this->apiResponse(201, 'Quota create Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quota  $quota
     * @return \Illuminate\Http\Response
     */
    public function show(Quota $quota)
    {
        return $this->apiResponseResourceCollection(200, 'Quota', QuotaResource::make($quota));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quota  $quota
     * @return \Illuminate\Http\Response
     */
    public function edit(Quota $quota)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quota  $quota
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quota $quota)
    {
        $quotaValidate =  $request->validate([
            'name' => 'required|string|unique:quotas,name,' . $quota->name ,
        ]);
      
        $quota->update($quotaValidate);
        return $this->apiResponse(201, 'quotas update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quota  $quota
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quota $quota)
    {
        $quota->delete();
        return $this->apiResponse(201, 'Quota Delete Successfully');
    }

    public function quotaList(){
        $quota = Quota::all();
        return $this->apiResponseResourceCollection(200, 'All Quota', QuotaResource::collection($quota));
    }


}
