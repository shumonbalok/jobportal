<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Resources\GradeResource;
use App\Http\Resources\PaymentResource;
use App\Models\AppliedJob;
use App\Models\Job;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserAdmission;
use App\Models\WorkerPayment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $userPayment = auth()->user()->payments()->with('users' , 'jobs' , 'admissions','workerPayments.users')->get();

        return $this->apiResponseResourceCollection(200, 'User Payment', PaymentResource::collection($userPayment));

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
    public function marchentPayment(Request $request , $id)
    {

        // $request->validated();
        $appliedJob = AppliedJob::with('jobs')->find($id);
        $marchent = auth()->user();
        $fee = $appliedJob->jobs->fee + $appliedJob->jobs->service_fee;
        if($marchent->balance < $appliedJob->jobs->fee){
            return $this->apiResponse(201, 'Balance Insuffcine');
        }else{
            $paymentSend = auth()->user()->decrement('balance' ,  $appliedJob->jobs->fee);
        }


        if($request->methods == Payment::ABEDOK){
            $methods =  Payment::ABEDOK;
        }elseif($request->methods == Payment::BKASH){
            $methods = Payment::BKASH;
        }elseif($request->methods  == Payment::ROCKET){
            $methods = Payment::ROCKET;
        }elseif($request->methods ==  Payment::CARD){
            $methods = Payment::CARD;
        }

        if ($marchent->balance < $fee) {
            return $this->apiResponse(201, 'Balance Insuffcine');
        } else {
            $comissionsMarchent  = $appliedJob->jobs->service_fee * ($marchent->commissions / 100);
            $adminProfit = $appliedJob->jobs->service_fee - $comissionsMarchent;
            $adminBalanceGet = $adminProfit + $appliedJob->jobs->fee;
            $marchentPayment = $marchent->decrement('balance', $fee);
            $marchentProfit = $marchent->increment('balance', $comissionsMarchent);
            $marchentCommssionProfit = $marchent->increment('profit', $comissionsMarchent);
            $admin = User::find(1)->increment('balance', $adminBalanceGet);
            $adminProfit = User::find(1)->increment('profit', $adminProfit);
        }
         $payment = Payment::create([
            'user_id' => $appliedJob->user_id,
            'job_id' => $appliedJob->job_id,
            'balance' => $fee,
            'methods' =>  $methods,
            'transation_number' =>$this->generateNumberCode(),
        ]);
         $appliedJob->update([
            'status' =>1
        ]);
       $payment->workerPayments()->create([
            'user_id' => $marchent->id,
        ]);
        return $this->apiResponse(201, 'Congratulations Your Payment  Successfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
          dd($payment);
         $payment->update([
            'status'=> 1
        ]);
        return $this->apiResponse(201, ' Your Payment  Successfully Done');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {

    }
     public function adminPayment()
    {

        $AdminPayment = Payment::with('users' , 'jobs' , 'admissions','workerPayments.users')->get();

        return $this->apiResponseResourceCollection(200, 'User Payment', PaymentResource::collection($AdminPayment));

    }

    public function generateNumberCode()
    {
        $transation_number = null;
        do {
            $transation_number =   'A'.(random_int(100000, 999999));
        } while (Payment::where("transation_number", "=", $transation_number)->first());
        return $transation_number;
    }

   public function marchentAdmissionPayment(Request $request, $id){
        $userAdmission = UserAdmission::with('admission')->find($id);
        $marchent = auth()->user();
        $fee = $userAdmission->admission->application_fee + $userAdmission->admission->service_fee;
        if($marchent->balance < $userAdmission->admission->application_fee){
            return $this->apiResponse(201, 'Balance Insuffcine');
        }else{
            $paymentSend = auth()->user()->decrement('balance' ,  $userAdmission->admission->application_fee);
        }


        if($request->methods == Payment::ABEDOK){
            $methods =  Payment::ABEDOK;
        }elseif($request->methods == Payment::BKASH){
            $methods = Payment::BKASH;
        }elseif($request->methods  == Payment::ROCKET){
            $methods = Payment::ROCKET;
        }elseif($request->methods ==  Payment::CARD){
            $methods = Payment::CARD;
        }

        if ($marchent->balance < $fee) {
            return $this->apiResponse(201, 'Balance Insuffcine');
        } else {
            $comissionsMarchent  = $userAdmission->admission->service_fee * ($marchent->commissions / 100);
            $adminProfit = $userAdmission->admission->service_fee - $comissionsMarchent;
            $adminBalanceGet = $adminProfit + $userAdmission->admission->application_fee;
            $marchentPayment = $marchent->decrement('balance', $fee);
            $marchentProfit = $marchent->increment('balance', $comissionsMarchent);
            $marchentCommssionProfit = $marchent->increment('profit', $comissionsMarchent);
            $admin = User::find(1)->increment('balance', $adminBalanceGet);
            $adminProfit = User::find(1)->increment('profit', $adminProfit);
        }
        $payment = Payment::create([
            'user_id' => $userAdmission->user_id,
            'admission_id' => $userAdmission->admission_id,
            'balance' =>$fee,
            'methods' =>  $methods,
            'transation_number' =>$this->generateNumberCode(),
            'payment_status' => 1
        ]);

        $userAdmission->update([
            'admission_status' =>1
        ]);
        $payment->workerPayments()->create([
            'user_id' => $marchent->id,
        ]);

        return $this->apiResponse(201, 'Congratulations Your Payment  Successfully');
    }
    
    public function paymentUpdate(Request $request,  $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->update([
            'status' => 1
        ]);
        return $this->apiResponse(201, ' Your Payment  Successfully Done');
    }






}
