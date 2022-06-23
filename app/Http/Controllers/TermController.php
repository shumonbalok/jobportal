<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
     public function show(Term $term)
    {
        return $this->apiResponse(201,'Term',$term);
    }
    public function update(Request $request,Term $term)
    {
        $term->update($request->all());
        return $this->apiResponse(200,'Term Updated Successfull');
    }
}
