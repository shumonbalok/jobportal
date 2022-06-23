<?php

namespace App\Http\Controllers\Address;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Http\Resources\AddressResorce;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

class AddressController extends Controller
{
     public function index()
    {
      //  dd('hello');
        $addresses=Address::with('district','upazila','postOffice')->get();
        return $this->apiResponseResourceCollection(200,'Details',AddressResorce::collection($addresses));
    }

    public function store(AddressRequest $request)
    {
        if($request->user_id)
        {
            $user=User::findOrfail($request->user_id);
        }
        else
        $user=auth()->user();
        $input=$request->validated();
        $addressPresent= Address::where('user_id',$user->id)->where('type',Address::PRESENT)->first();
        $addressPermanent = Address::where('user_id', $user->id)->where('type', Address::PERMANENT)->first();
        $addressSame = Address::where('user_id', $user->id)->where('type', Address::SAME)->first();
        $count=$user->address()->count();
        if($count==0)
            $user->increment('profile_strength', 20);
    //    dd($addressSame);
     //   dd($input);
            $present['user_id']=$user->id;
            $present['care_of']=$input['care_of_present'];
            $present['details']=$input['details_present'];
            $present['district_id']=$input['district_id_present'];
            $present['upazila_id']=$input['upazila_id_present'];
            $present['post_office_id']=$input['post_office_id_present'];
            $present['postal_code']=$input['postal_code_present'];
            if($request->same_as==1)
            {
                $present['type']=Address::SAME;
            if ($addressSame == null && $addressPresent == null) {
                Address::create($present);
            } else {
                if($addressSame == null)
                $addressPresent->update($present);
                else
                $addressSame->update($present);
            }
                if($addressPermanent != null )
                $addressPermanent->delete();
            }
            else{
                $present['type']=Address::PRESENT;
            if ($addressPresent == null && $addressSame == null) {
             Address::create($present);
            } else {
                if ($addressPresent == null)
                $addressSame->update($present);
                else
                $addressPresent->update($present);
            }
                //Permanent

                $permanent['user_id']=$user->id;
                $permanent['care_of']=$input['care_of_permanent'];
                $permanent['details']=$input['details_permanent'];
                $permanent['district_id']=$input['district_id_permanent'];
                $permanent['upazila_id']=$input['upazila_id_permanent'];
                $permanent['post_office_id']=$input['post_office_id_permanent'];
                $permanent['postal_code']=$input['postal_code_permanent'];
                $permanent['type']=Address::PERMANENT;
                if($addressPermanent==null)
                Address::create($permanent);
                else
                $addressPermanent->update($permanent);
            }



          //  dd($user->address()->count());
        // if($user->address()->count()>=1) $user->increment('profile_strength',20);

        return response()->json([
            'profile_strength' => $user->profile_strength,
            'message' => 'Address Saved',
        ], 201);
    }


    public function show(Address $address)
    {
        $address->load('district', 'upazila', 'postOffice');
        return $this->apiResponse(200,'Details',AddressResorce::make($address));
    }


    public function update(AddressRequest $request,Address $address)
    {
        $userId=auth()->id();
        $address_permanent=Address::where('user_id',$userId)->where('type',Address::PERMANENT)->first();

        $input=$request->validated();

            $present['user_id']=$userId;
            $present['care_of']=$input['care_of_present'];
            $present['details']=$input['details_present'];
            $present['district_id']=$input['district_id_present'];
            $present['upazila_id']=$input['upazila_id_present'];
            $present['post_office_id']=$input['post_office_id_present'];
            $present['postal_code']=$input['postal_code_present'];
            if($request->same_as==1)
            {
                $present['type']=Address::SAME;
                $address->update($present);
                if($address_permanent){
                    $address_permanent->delete();
                }

            }
            else{
                $present['type']=Address::PRESENT;
                $address->update($present);
                //Permanent


                $permanent['user_id']=$userId;
                $permanent['care_of']=$input['care_of_permanent'];
                $permanent['details']=$input['details_permanent'];
                $permanent['district_id']=$input['district_id_permanent'];
                $permanent['upazila_id']=$input['upazila_id_permanent'];
                $permanent['post_office_id']=$input['post_office_id_permanent'];
                $permanent['postal_code']=$input['postal_code_permanent'];
                $permanent['type']=Address::PERMANENT;
                 if($address_permanent){
                     $address_permanent->update($permanent);
                 }
                 else{
                     Address::create($permanent);
                 }

            }


            return $this->apiResponse(200,'Address Data Saved Successfully');
    }
    public function destroy(Address $address)
    {
        $address->delete();
         return $this->apiResponse(200,'Address Data Deleted Successfully');
    }

    //  public function forceDelete($id)
    // {
    //     $address= Address::withTrashed()->find($id);
    //     $address->forceDelete();
    //     return $this->apiResponse(201, 'Address  Delete Successfully');
    // }
}
