<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function show(Policy $policy)
    {
        return $this->apiResponse(201,'Policy',$policy);
    }
    public function update(Request $request,Policy $policy)
    {
        $policy->update($request->all());
        return $this->apiResponse(200,'Policy Updated Successfull');
    }
}
