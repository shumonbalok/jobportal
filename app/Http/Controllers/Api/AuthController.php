<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResorce;
use App\Http\Resources\BasicInfoResource;
use App\Http\Resources\ExperienceResource;
use App\Http\Resources\SkillResource;
use App\Http\Resources\UserResorce;
use App\Http\Resources\AuthResource;
use App\Models\MerchantUser;
use App\Models\User;
use App\Notifications\SuccesfullNotification;
use App\Services\OtpService;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Twilio;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'phone' => 'required',
        ]);

        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            $user->update([
                'otp' => $this->otpSend(),
                'expiry_otp' => Carbon::now()->addSecond(60),
                'status' => 1,
            ]);
            (new OtpService())->sendOtpNotification($request->phone , $user->otp);
            return response()->json([
                'otp' => $user->otp,
                'message' => 'User Send Otp ',
            ], 201);
        }
        $createUser = User::create([
            'phone' => $request->phone,
            'otp' => $this->otpSend(),
            'password' => Hash::make('123123123'),
            'expiry_otp' => Carbon::now()->addSecond(60),

        ]);
        (new OtpService())->sendOtpNotification($request->phone , $createUser->otp);
        return response()->json([
            'otp' => $createUser->otp,
            'message' => 'User successfully registered',
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'otp' => 'required|string',
        ]);

        $user = User::where('phone', $request->phone)->first();

        if (!$user) {
            return response()->json([
                'message' => 'These credentials do not match our records.'
            ], 404);
        }

        if(Carbon::now() >  $user->expiry_otp){
            return response()->json([
                'message' => 'These Otp Expried.'
            ], 404);
        }

        if ($request->otp !==  $user->otp) {
            return response()->json([
                'message' => 'These Otp do not match our records.'
            ], 401);
        }

        $token = $user->createToken('my-app-token')->plainTextToken;
        $user->update([
            'otp' => $this->otpSend(),
        ]);



        return response()->json([
            'user' => AuthResource::make($user),
            'token' => $token
        ], 201);
    }



    public function logout(Request $request)
    {

        $request->user()->currentAccessToken()->delete();
        return response(['message' => 'You have been successfully logged out.'], 200);
    }

    public function changeProfile(Request $request)
    {
        if($request->user_id)
        $user=User::findOrfail($request->user_id);
        else
        $user=auth()->user();
        $request->validate(
            [
                'email' => 'required|string|email|unique:users,email,' . auth()->id(),
                'birth_date' => 'required',
                'name' => 'required',
                'gender' => 'required',
            ]
        );
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'birth_date' => Carbon::parse($request->birth_date)->format('Y-m-d'),
            'gender' => $request->gender,
        ]);
        return $this->apiResponse(201, 'Update User Successfully');
    }

    // public function changePassword(Request $request)
    // {

    //     $validator = $request->validate(
    //         [
    //             'old_password' => 'required|min:8',
    //             'password' => 'required|min:8|confirmed',
    //             'password_confirmation' => 'required|min:9',
    //         ],
    //     );
    //     // $data = $request->all();
    //     if (Hash::check($validator['old_password'], auth()->user()->password)) {
    //         auth()->user()->update([
    //             'password' => Hash::make($request->password),
    //         ]);
    //         return response()->json([
    //             'message' => 'Password Update Succesfully',
    //         ], 201);
    //     } else {
    //         return response()->json([
    //             'message' => 'Current_Password Is incorrect ',
    //         ], 404);
    //     }
    // }

    public function changeProfilepicture(Request $request)
    {


        $request->validate([
            'photo' => 'required'
        ]);

        if ($request->hasFile('photo')) {
            $fileName = Rand() . '.' . $request->file('photo')->getClientOriginalExtension();

            $profile = $request->file('photo')->storeAs('userImage', $fileName, 'public');
        }

        auth()->user()->update([
            'photo' => $profile,
        ]);

        return response()->json([
            'message' => 'Photo Upload Successfully ',
        ], 201);
    }

    public function show()
    {
        $user = auth()->user();
        //  dd($user->experience);
        // return response()->json([
        //     'user' => $user,
        //     'Basic_Info'=>!$user->basicInfo ? 'N/A' : BasicInfoResource::make($user->basicInfo),
        //     'Experience'=> !$user->experience ? 'N/A' : ExperienceResource::collection($user->experience),
        //     'Skills' =>!$user->skill ? 'N/A' : SkillResource::collection($user->skill),
        //     'Address'=>!$user->address? 'N/A' : AddressResorce::collection($user->address),
        //     'message' => 'User Information ',

        // ], 201);

        $user->address->load('district', 'upazila', 'postOffice');
        $user->load('roles');

        // dd($user->load('basicInfo','skill' ,'experience' ,'address'));
        return $this->apiResponse(200, 'User Details', UserResorce::make($user));
    }

    public function adminlogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'min:9'],
        ]);

        $user = User::where('email', $request->email)->first();

         if ($user && $user->type == 1) {
             return response()->json([
                 'message' => 'Page Not Found'
             ], 422);
         }

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'These credentials do not match our records.'
            ], 422);
        }


        $token = $user->createToken('my-app-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'photo' => setImage($user->photo),
            'token' => $token
        ], 201);
    }

    public function adminRegister(Request $request)
    {


        $request->validate([
            'email' => ['required', 'string', 'email', 'unique:users,email'],
            'password' => ['required', 'min:9', 'confirmed'],
            'phone' => ['required', 'unique:users,phone', 'digits:11'],
        ]);

       $user =  User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'type' => $request->type,
            'commission' => $request->commission,
            'password' => Hash::make($request->password),
            'otp' => $this->otpSend(),
            'type'      => $request->type
        ]);

        // $user->merchantUser()->create([
        //     'merchant_id' => auth()->id(),
        // ]);

            MerchantUser::create([
                'user_id' => $user->id,
                'merchant_id' => auth()->id(),
            ]);

            $Successfully = [
                'title' => 'User Create',
                'message' => $user->name. " User Create Succsfully",
                'url' => '/user/merchent'
            ];


            $user->notify(new SuccesfullNotification($Successfully));
            auth()->user()->notify(new SuccesfullNotification($Successfully));

        return response()->json([
            'user' => $user->id,
            'message' => 'User successfully registered',
        ], 201);
    }

    public function ProfileUpload(Request $request){
        $request->validate([
            'photo' => ['required', ],
        ]);
        $user = auth()->user();
        if ($request->hasFile('photo')) {

            if (File::exists('storage/userImage/' . $user->allphoto)) {
                File::delete('storage/userImage/' . $user->allphoto);
            }
            $fileName = Rand() . '.' . $request->file('photo')->getClientOriginalExtension();

            $photos = $request->file('photo')->storeAs('userImage', $fileName, 'public');

        }
        User::find($user->id)->update([
            'photo' => $photos,
        ]);

        return response()->json([
            'photo' => setImage($photos),
            'message' => 'Photo Upload Sucessfully',
        ], 201);

    }

    public function otpSend()
    {
        $otp = "";
        do {
            $otp = (random_int(1000, 9999));
        } while (User::where("otp", "=", $otp)->first());




        return $otp;
    }
}
