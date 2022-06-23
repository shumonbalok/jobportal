<?php

namespace App\Http\Controllers\Admin;

use App\Services\FirebaseNotificationService;
use App\Notifications\SuccesfullNotification;
use App\Services\OtpService;
use Twilio;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use function PHPUnit\Framework\returnSelf;

class UserController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit(User $user)
    {


        return view('dashboard.user.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $input = $request->all();
        $user->update($input);
        return $this->apiResponse(200,'User Updated Successfully');
    }

    public function destroy(User $user)
    {
      $user->delete();
      return $this->apiResponse(200,'User Deleted Successfully');
    }

    public function editProfile()
    {
        $this->checkPermission('profile.edit');
        $user = auth()->user();
        return view('dashboard.user.edit-profile', compact('user'));
    }

    public function change_password()
    {
        $this->checkPermission('profile.edit');
        return view('dashboard.user.change_password');
    }
    
    public  function balanceReload(){
       $balance =  auth()->user()->balance;
       return response()->json([
           'balance' => (string)$balance
       ]);
    }

    public function sms(){


       $user =  Twilio::message('+8801820840336',"hello");

        dd( $user);


    }
    public function notice(){

      $Successfully = [
            'title' => 'Payment Done',
            'message' => "Payment Was  Succsfully",
        ];
        
        auth()->user()->notify(new SuccesfullNotification($Successfully));

    }
}
