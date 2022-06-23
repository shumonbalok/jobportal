<?php

namespace App\Http\Controllers;

use App\Http\Resources\PayementSendMerchantResource;
use App\Http\Resources\PaymentResource;
use App\Models\AppliedJob;
use App\Models\Payment;
use App\Models\PaymentSendMerchant;
use App\Models\User;
use App\Models\UserAdmission;
use Illuminate\Http\Request;

class PaymentSendMerchantController extends Controller
{

    public function index(){
        $paymentSendMerchant = Payment::all();
        $paymentSendMerchant->load('users');
        $paymentSendMerchant->load('jobs');
        $paymentSendMerchant->load('admissions');
        return $this->apiResponseResourceCollection( 200,"Payment List",PaymentResource::collection($paymentSendMerchant));
    }

    public function paymentSendMerchant(Request $request , $id){

        PaymentSendMerchant::create([
            'payment_id' => $id,
            'merchant_id' => $request->merchant_id
        ]);
        return $this->apiResponse( 201,"Transfer Successfully");
    }

    public function paymentSendMerchantList(){
        $merchantPaymentList = PaymentSendMerchant::where('merchant_id', auth()->id())->get();
        $merchantPaymentList->load('payments');
        return $this->apiResponseResourceCollection(200, "all list" , PayementSendMerchantResource::collection($merchantPaymentList));
    }
    public function paymentSendMerchantPayNow($id){
        $payment = Payment::with('jobs')->findOrFail($id);
        $marchent = auth()->user();
        $fee = $payment->jobs->fee + $payment->jobs->service_fee;


        if($marchent->balance < $fee){
            return $this->apiResponse(201, 'Balance Insuffcine');
        }
        else{
            $payment->update([
                'payement_status' => 1
            ]);

            $comissionsMarchent  =  $payment->jobs->service_fee * ($marchent->commissions / 100);
            $adminProfit = $payment->jobs->service_fee - $comissionsMarchent;
            $adminBalanceGet = $adminProfit + $payment->jobs->fee;
            $marchentPayment = $marchent->decrement('balance', $fee);
            $marchentProfit = $marchent->increment('balance', $comissionsMarchent);
            $marchentCommssionProfit = $marchent->increment('profit', $comissionsMarchent);
            $admin = User::find(1)->increment('balance', $adminBalanceGet);
            $adminProfit = User::find(1)->increment('profit', $adminProfit);
        }
        return $this->apiResponse( 201,"Payment Successfully");
    }
    public function paymentSendMerchantadmissionPayNow($id){
        $payment = Payment::findOrFail($id);
        $marchent = auth()->user();
        $fee = $payment->admissions->fee + $payment->admissions->service_fee;

        if($marchent->balance < $fee){
            return $this->apiResponse(201, 'Balance Insuffcine');
        }
        $payment->update([
            'payement_status' => 1
        ]);
        $comissionsMarchent  =  $payment->admissions->service_fee * ($marchent->commissions / 100);
        $adminProfit = $payment->admissions->service_fee - $comissionsMarchent;
        $adminBalanceGet = $adminProfit + $payment->job->fee;
        $marchentPayment = $marchent->decrement('balance', $fee);
        $marchentProfit = $marchent->increment('balance', $comissionsMarchent);
        $marchentCommssionProfit = $marchent->increment('profit', $comissionsMarchent);
        $admin = User::find(1)->increment('balance', $adminBalanceGet);
        $adminProfit = User::find(1)->increment('profit', $adminProfit);

        return $this->apiResponse( 201,"Payment Successfully");
    }

    public function merchantJobPayment( Request $request,$id){
        $jobApplied = AppliedJob::findOrFail($id);
        $jobApplied->users()->attach($request->merchant_id);
        return $this->apiResponse( 201,"Transfer Successfully");

    }

    public function merchantAdmissionPayment(Request $request, $id){

        $admissionApplied = UserAdmission::findOrFail($id);
        $admissionApplied->users()->attach($request->merchant_id);
        return $this->apiResponse( 201,"Transfer Successfully");
    }

}
