<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Http\Resources\UserNotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(){
        $notifications = [];
        foreach (auth()->user()->notifications as $data){
            foreach ($data->data as $message){
                $notifications[] = $message;
            }
        }
        // auth()->user()->notifications()->delete();
        // dd(auth()->user()->notifications);
            return response()->json([
                "notifications" => $notifications,
            ]);
    }
     public  function setting(){
        $notificationSetting = auth()->user();
       return $this->apiResponse(201,'User notification' , UserNotificationResource::make($notificationSetting));
    }

   public function settingStore(Request $request){

 
       
        if($request->notify_type != null ){
            auth()->user()->update([
                'notify_type' => $request->notify_type,
            ]);
        }
        if( $request->notify_fav_type  != null) {
            auth()->user()->update([
                'notify_fav_type' => $request->notify_fav_type,
            ]);
        }
        if($request->notify_hired_type  != null){
            auth()->user()->update([
                'notify_hired_type' => $request->notify_hired_type,
            ]);
        }
        if( $request->notify_unemployed_type  != null) {
            auth()->user()->update([

                'notify_unemployed_type' => $request->notify_unemployed_type,
            ]);
        }

        return $this->apiResponse(201 ,'Save Successfully');
    }
}
