<?php

namespace App\Http\Controllers\Admission;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserAdmissionResorce;
use App\Models\Admission;
use App\Models\AdmissionStatus;
use App\Models\User;
use App\Models\UserAdmission;
use App\Models\Payment;
use App\Notifications\SuccesfullNotification;
use Illuminate\Http\Request;
use LDAP\Result;

class UserAdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userAdmissions = UserAdmission::with('user', 'admission.group', 'admissionStatus', 'admission.userAdmission')->when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->get();

        return $this->apiResponseResourceCollection(201, 'User Admission Details List ', UserAdmissionResorce::collection($userAdmissions));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if ($request->user_id) $user = User::findOrfail($request->user_id);
        else $user = auth()->user();
        $admission = Admission::findOrfail($request->admission_id);
        $check=UserAdmission::where([['user_id',$user->id],['admission_id',$admission->id]])->first();
        if($check !=  null)
        return $this->apiResponse(200,'Already Applied');
        $gpa = 0;
        $group = null;
        foreach ($user->graduates as $result) {
            $result->load('examination', 'group');
            if ($result->examination->name == 'SSC' || $result->examination->name == 'HSC') {
                if ($result->examination->name == 'HSC') {
                    $group = $result->group;


                    if ($admission->min_gpa != null && $admission->min_gpa > $result->result)

                        return $this->apiResponse(404, 'User GPA not Enough For Application ');
                }
                $gpa += $result->result;
            }
        }
        if ($admission->group != null && $admission->group != $group)
            return $this->apiResponse(404, 'To Apply for this Admission User must be from ' . $admission->group->name);
        if ($admission->min_gpa_total != null && $admission->min_gpa_total > $gpa)
            return $this->apiResponse(404, 'User Total GPA not Enough For Application');
        $input = $request->validate([
            'admission_id'  => 'required|exists:admissions,id',

        ]);
        $input['user_id'] = $user->id;


        if ($request->methods == Payment::ABEDOK) {
            $methods =  Payment::ABEDOK;
        } elseif ($request->methods == Payment::BKASH) {
            $methods = Payment::BKASH;
        } elseif ($request->methods  == Payment::ROCKET) {
            $methods = Payment::ROCKET;
        } elseif ($request->methods ==  Payment::CARD) {
            $methods = Payment::CARD;
        }

        $fee = $admission->application_fee + $admission->service_fee;
        if (auth()->user()->type == 0) {
            $marchent = auth()->user();
            foreach ($user->payments()->pluck('admission_id') as $admissionId) {
                if ($admissionId == $request->admission_id) {
                    return $this->apiResponse(201, 'Already Balance  Send');
                }
            }
            if ($marchent->balance < $fee) {
                return $this->apiResponse(201, 'Balance Insuffcine');
            } else {

                $comissionsMarchent  = $admission->service_fee * ($marchent->commissions / 100);
                $adminProfit = $admission->service_fee - $comissionsMarchent;
                $adminBalanceGet = $adminProfit + $admission->fee;
                $marchentPayment = $marchent->decrement('balance', $fee);
                $marchentProfit = $marchent->increment('balance', $comissionsMarchent);
                $marchentCommssionProfit = $marchent->increment('profit', $comissionsMarchent);
                $admin = User::find(1)->increment('balance', $adminBalanceGet);
                $adminProfit = User::find(1)->increment('profit', $adminProfit);

                $admissionApplied = UserAdmission::create($input);
                $admissionApplied->users()->attach($marchent->id);
            }
        } else {
            if ($user->balance < $fee) {
                return $this->apiResponse(201, 'Balance Insuffcine');
            } else {

                foreach ($user->payments()->pluck('admission_id') as $admission_id) {
                    if ($admission_id == $request->admission_id) {
                        return $this->apiResponse(201, 'Already Balance Send');
                    }
                }
                $admissionApplied = UserAdmission::create($input);
                $userPayment = $user->decrement('balance', $fee);
            }
        }
        $payments =   Payment::create([
            'user_id' => $user->id,
            'admission_id' => $admission->id,
            'balance' => $admission->application_fee,
            'methods' =>  $methods,
            'transation_number' => $this->generateNumberCode(),
        ]);

        $SuccessfullyPaement = [
            'title' =>"Addmission Submit Was Successful ".$admission->name,
            'message' => "Addmission Submit Was Successful ".$admission->name,
        ];
        $user->notify(new SuccesfullNotification($SuccessfullyPaement));

        if (auth()->user()->type == 0) {
            $payments->workerPayments()->create([
                'user_id' => $marchent->id,
            ]);
        }
        $admissionApplied->admissionStatus()->create([
                       'description' => "Application Submission",
                       'short_name' => "Applied",
                       'type'       => 0,
                       'status'     => 1
                   ]);
                    $admissionApplied->admissionStatus()->create([
                        'description' => "Payment Successfull",
                        'short_name' => "Applied",
                        'type'       => 1,
                        'status'     => 0
                    ]);
                    $admissionApplied->admissionStatus()->create([
                        'description' => "Exam Center Location",
                        'short_name' => "Preli",
                        'type'       => 2,
                        'status'     => 0
                    ]);
                    $admissionApplied->admissionStatus()->create([
                        'description' => "Preli Result",
                        'short_name' => "Preli",
                        'type'       => 3,
                        'status'     => 0
                    ]);
                    $admissionApplied->admissionStatus()->create([
                        'description' => "Written Exam Center Location",
                        'short_name' => "Written",
                        'type'       => 4,
                        'status'     => 0
                    ]);
                    $admissionApplied->admissionStatus()->create([
                        'description' => "Written Exam Result",
                        'short_name' => "Written",
                        'type'       => 5,
                        'status'     => 0
                    ]);
                  $admissionApplied->admissionStatus()->create([
                       'description' => "Viva Location",
                       'short_name' => "Viva",
                       'type'       => 6,
                       'status'     =>0
                   ]);
                    $admissionApplied->admissionStatus()->create([
                       'description' => "Viva Result",
                       'short_name' => "Viva",
                       'type'       => 7,
                       'status'     =>0
                   ]);
                    $admissionApplied->admissionStatus()->create([
                       'description' => "Congratulations",
                       'short_name' => "Congrats",
                       'type'       => 8,
                       'status'     =>0
                   ]);



        return $this->apiResponse(201, 'Applied Successfully');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserAdmission  $userAdmission
     * @return \Illuminate\Http\Response
     */
    public function show(UserAdmission $userAdmission)
    {
        // dd($userAdmission);
        $userAdmission->load('admissionStatus');
        return $this->apiResponse(200, 'User Admission Details', UserAdmissionResorce::make($userAdmission));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserAdmission  $userAdmission
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserAdmission  $userAdmission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserAdmission $userAdmission)
    {
        if ($request->user_id) $user = $request->user_id;
        else $user = auth()->id();
        $input = $request->validate([
            'admission_id'  => 'required|exists:admissions,id',
        ]);

        $input['user_id'] = $user;
        $input['roll'] = $request->roll;
        $userAdmission->update($input);
        return $this->apiResponse(200, 'User Application Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserAdmission  $userAdmission
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserAdmission $userAdmission)
    {
        $userAdmission->delete();
        return $this->apiResponse(200, 'User Application Deleted successfully');
    }
    //  public function forceDelete($id)
    // {
    //     $userAdmission= UserAdmission::withTrashed()->find($id);
    //     $userAdmission->forceDelete();
    //     return $this->apiResponse(201, 'User Admission  Delete Successfully');
    // }
    public function statusUpdate($id)
    {
        $userAdmisiion = AdmissionStatus::with(['admissionUser.admission' , 'admissionUser.users'])->findOrfail($id);

        $userAdmisiion->update(['status' => 1]);

        $SuccessfullyPaement = [
            'message' => $userAdmisiion->description." ".$userAdmisiion->admission->name,
        ];

        foreach($userAdmisiion->users as $admissions){
            $admissions->users->notify(new SuccesfullNotification($SuccessfullyPaement));
        }
        return $this->apiResponse(201, 'User Admission  Status Updated');
    }
    public function generateNumberCode()
    {
        $transation_number = null;
        do {
            $transation_number =   'A' . (random_int(100000, 999999));
        } while (Payment::where("transation_number", "=", $transation_number)->first());
        return $transation_number;
    }

    public function appliedList()
    {
        return $this->apiResponseResourceCollection(200,'Applied Admission List', UserAdmissionResorce::collection(auth()->user()->userAdmission));
    }

    public function userAdmissionStore( Request $request){
        $user = User::findOrfail($request->user_id);
        $admission = Admission::findOrfail($request->admission_id);
        $check=UserAdmission::where([['user_id',$user->id],['admission_id',$admission->id]])->first();
        if($check !=  null)
        return $this->apiResponse(200,'Already Applied');
        $gpa = 0;
        $group = null;
        foreach ($user->graduates as $result) {
            $result->load('examination', 'group');
            if ($result->examination->name == 'SSC' || $result->examination->name == 'HSC') {
                if ($result->examination->name == 'HSC') {
                    $group = $result->group;


                    if ($admission->min_gpa != null && $admission->min_gpa > $result->result)

                        return $this->apiResponse(404, 'User GPA not Enough For Application ');
                }
                $gpa += $result->result;
            }
        }
        if ($admission->group != null && $admission->group != $group)
            return $this->apiResponse(404, 'To Apply for this Admission User must be from ' . $admission->group->name);
        if ($admission->min_gpa_total != null && $admission->min_gpa_total > $gpa)
            return $this->apiResponse(404, 'User Total GPA not Enough For Application');
        $input = $request->validate([
            'admission_id'  => 'required|exists:admissions,id',

        ]);
        $input['user_id'] = $user->id;
        $marchent = auth()->user();
        if($marchent->givePermissionTo('user.access')){

            if ($request->methods == Payment::ABEDOK) {
                $methods =  Payment::ABEDOK;
            } elseif ($request->methods == Payment::BKASH) {
                $methods = Payment::BKASH;
            } elseif ($request->methods  == Payment::ROCKET) {
                $methods = Payment::ROCKET;
            } elseif ($request->methods ==  Payment::CARD) {
                $methods = Payment::CARD;
            }
            $fee = $admission->application_fee + $admission->service_fee;

                $marchent = auth()->user();
                if ($marchent->balance < $fee) {
                    $admissionApplied = UserAdmission::create($input);
                    $admissionApplied->users()->attach($marchent->id);
                    $payments =   Payment::create([
                        'user_id' => $user->id,
                        'admission_id' => $admission->id,
                        'balance' => $admission->application_fee,
                        'methods' =>  $methods,
                        'transation_number' => $this->generateNumberCode(),
                        'payment_status' => 0
                    ]);
                    $payments->workerPayments()->create([
                        'user_id' => $marchent->id,
                    ]);
                } else {
                    $comissionsMarchent  = $admission->service_fee * ($marchent->commissions / 100);
                    $adminProfit = $admission->service_fee - $comissionsMarchent;
                    $adminBalanceGet = $adminProfit + $admission->fee;
                    $marchentPayment = $marchent->decrement('balance', $fee);
                    $marchentProfit = $marchent->increment('balance', $comissionsMarchent);
                    $marchentCommssionProfit = $marchent->increment('profit', $comissionsMarchent);
                    $admin = User::find(1)->increment('balance', $adminBalanceGet);
                    $adminProfit = User::find(1)->increment('profit', $adminProfit);

                    $admissionApplied = UserAdmission::create($input);
                    $admissionApplied->users()->attach($marchent->id);
                    $payments =   Payment::create([
                        'user_id' => $user->id,
                        'admission_id' => $admission->id,
                        'balance' => $admission->application_fee,
                        'methods' =>  $methods,
                        'transation_number' => $this->generateNumberCode(),
                    ]);
                    $payments->workerPayments()->create([
                        'user_id' => $marchent->id,
                        
                    ]);
                }

        

            $SuccessfullyPaement = [
                'message' => "Addmission Submit Was Successful ".$admission->name,
            ];
            $user->notify(new SuccesfullNotification($SuccessfullyPaement));

        }else{
            $admissionApplied = UserAdmission::create($input);
        }
        $admissionApplied->admissionStatus()->create([
                       'description' => "Application Submission",
                       'short_name' => "Applied",
                       'type'       => 0,
                       'status'     => 1
                   ]);
                    $admissionApplied->admissionStatus()->create([
                        'description' => "Payment Successfull",
                        'short_name' => "Applied",
                        'type'       => 1,
                        'status'     => 0
                    ]);
                    $admissionApplied->admissionStatus()->create([
                        'description' => "Exam Center Location",
                        'short_name' => "Preli",
                        'type'       => 2,
                        'status'     => 0
                    ]);
                    $admissionApplied->admissionStatus()->create([
                        'description' => "Preli Result",
                        'short_name' => "Preli",
                        'type'       => 3,
                        'status'     => 0
                    ]);
                    $admissionApplied->admissionStatus()->create([
                        'description' => "Written Exam Center Location",
                        'short_name' => "Written",
                        'type'       => 4,
                        'status'     => 0
                    ]);
                    $admissionApplied->admissionStatus()->create([
                        'description' => "Written Exam Result",
                        'short_name' => "Written",
                        'type'       => 5,
                        'status'     => 0
                    ]);
                  $admissionApplied->admissionStatus()->create([
                       'description' => "Viva Location",
                       'short_name' => "Viva",
                       'type'       => 6,
                       'status'     =>0
                   ]);
                    $admissionApplied->admissionStatus()->create([
                       'description' => "Viva Result",
                       'short_name' => "Viva",
                       'type'       => 7,
                       'status'     =>0
                   ]);
                    $admissionApplied->admissionStatus()->create([
                       'description' => "Congratulations",
                       'short_name' => "Congrats",
                       'type'       => 8,
                       'status'     =>0
                   ]);



        return $this->apiResponse(201, 'Applied Successfully');
    }
    
    public function addmissionMarchent(){
        $userAdmissions =auth()->user()->appliedMarchentAdmission()->with([
            'user', 'admission.group', 'admissionStatus', 'admission.userAdmission'
        ])->get();
      
        return $this->apiResponseResourceCollection(201, 'User Admission Details List ', UserAdmissionResorce::collection($userAdmissions));
    }
}
