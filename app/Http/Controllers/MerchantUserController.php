<?php

namespace App\Http\Controllers;

use App\Http\Resources\MerchantUserResource;
use App\Http\Resources\UserResorce;
use App\Models\MerchantUser;
use App\Models\User;
use Illuminate\Http\Request;

class MerchantUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $merchantUserList = MerchantUser::where('merchant_id' , auth()->id())->get();
        $merchantUserList->load('users');
        $merchantUserList->load('merchent');
       
        return $this->apiResponseResourceCollection(200, 'All Passing Year', MerchantUserResource::collection($merchantUserList));

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MerchantUser  $merchantUser
     * @return \Illuminate\Http\Response
     */
    public function show(MerchantUser $merchantUser)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MerchantUser  $merchantUser
     * @return \Illuminate\Http\Response
     */
    public function edit(MerchantUser $merchantUser)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MerchantUser  $merchantUser
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MerchantUser $merchantUser)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MerchantUser  $merchantUser
     * @return \Illuminate\Http\Response
     */
    public function destroy(MerchantUser $merchantUser)
    {
        //
    }

    public function marchentByAdmin(){
        $merchantUserList = MerchantUser::where('merchant_id' , auth()->id())->get();
        $merchantUserList->load('users');
        $merchantUserList->load('merchent');
         
        return $this->apiResponseResourceCollection(200, 'All Passing Year', MerchantUserResource::collection($merchantUserList));
    }
    
     public  function  allmarchent(){
        $user = User::whereKeyNot(1)->where('type', 0)->get();
          $user->load('basicInfo');
        $user->load('experience');
        $user->load('skill');
        $user->load('address');
        $user->load('graduates');
        $user->load('higherGraduates');
        $user->load('allphoto');
        $user->load('allphotosub');
        
              return response()->json($user , 201);
    }

     public function merchantTransferUser(Request $request ,$user_id){


      
       $hello =  $request->validate([
            'merchant_id' => 'required'
        ]);

        $MarchentUser = MerchantUser::where('user_id' , $user_id)->first();
        if($MarchentUser == null){
            return $this->apiResponse(404, 'User not  Found');
        }

        $MarchentUser->update([
            'merchant_id' => $request->merchant_id
        ]);

        return $this->apiResponse(201, 'Transfer Successfully');
     }
}
