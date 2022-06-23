<?php

namespace App\Http\Controllers;

use App\Models\AllPhotoSub;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AllPhotoSubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
      
       


        if ($request->user_id) {
            $user = User::find($request->user_id);
            $user->load('allphoto');
        } else {
          $user = auth()->user();
            $user->load('allphoto');
        }

        if ($request->hasFile('certificate_photos_sub')) {
            $fileName = Rand() . '.' . $request->file('certificate_photos_sub')->getClientOriginalExtension();
            $certificate_photos_sub = $request->file('certificate_photos_sub')->storeAs('jobProfile/sub', $fileName, 'public');
        }
        
        AllPhotoSub::create([
            'user_id'                  => $user->id,
            'certificate_photos_sub'   => $certificate_photos_sub,
            'photos_name'              => $request->photos_name,
        ]);
       
    
       return $this->apiResponse(201, 'Photo  Create Successfully');
            
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AllPhotoSub  $allPhotoSub
     * @return \Illuminate\Http\Response
     */
    public function show(AllPhotoSub $allPhotoSub)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AllPhotoSub  $allPhotoSub
     * @return \Illuminate\Http\Response
     */
    public function edit(AllPhotoSub $allPhotoSub)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AllPhotoSub  $allPhotoSub
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'photos_name' => 'required',
            'certificate_photos_sub' => 'required'
        ]);


        if ($request->user_id) {
            $user = User::with('allphoto')->find($request->user_id);
        } else {
            $user = auth()->user()->with('allphoto')->first();
        }

        if ($request->hasFile('certificate_photos_sub')) {
            $fileName = Rand() . '.' . $request->file('certificate_photos_sub')->getClientOriginalExtension();
            $certificate_photos_sub = $request->file('certificate_photos_sub')->storeAs('jobProfile/sub', $fileName, 'public');
        }
        
        $user = User::findOrFail($id);
        $user->allphotosub()->update([
            'user_id'                  => $user->id,
            'certificate_photos_sub'   => $certificate_photos_sub,
            'photos_name'              => $request->photos_name,
        ]);
        return $this->apiResponse(201, 'Photo  update Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AllPhotoSub  $allPhotoSub
     * @return \Illuminate\Http\Response
     */
    public function destroy(AllPhotoSub $allPhotoSub)
    {
        //
    }
}
