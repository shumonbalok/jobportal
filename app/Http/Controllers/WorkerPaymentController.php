<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkerPaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Http\Resources\WorkerPaymentResource;
use App\Models\Job;
use App\Models\Payment;
use App\Models\WorkerPayment;
use Illuminate\Http\Request;

class WorkerPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


       $workingPayment =  auth()->user()->workerPayments()->with(['payments.users.address', 'payments.users.experience', 'payments.users.skill', 'payments.users.graduates.examination', 'payments.users.higherGraduates', 'payments.users.allphoto',
        'payments.users.allphotosub' ,'payments.jobs.department', 'payments.jobs.examination', 'payments.jobs.subject', 'payments.jobs.grade', 'payments.jobs.district', 'payments.users.basicInfo','payments.users.basicInfo.quota' , 'payments.admissions.university', 'payments.admissions.unit', 'payments.admissions.group'])->get();

        return $this->apiResponseResourceCollection(200, 'User Payment', WorkerPaymentResource::collection($workingPayment));


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
    public function store(WorkerPaymentRequest $request)
    {

        $user = auth()->user();

        $job = Job::find($request->job_id);

        if($request->methods == Payment::ABEDOK){
            if($user->balance < $job->fee){
                return $this->apiResponse(201, 'Balance Insuffcine');
            }else{
                $paymentSend = auth()->user()->decrement('balance' ,  $job->fee);
            }
            $methods =  Payment::ABEDOK;
        }elseif($request->methods == Payment::BKASH){
            $methods = Payment::BKASH;
        }elseif($request->methods  == Payment::ROCKET){
            $methods = Payment::ROCKET;
        }elseif($request->methods ==  Payment::CARD){
            $methods = Payment::CARD;
        }

       $payments =  Payment::create([
            'user_id' => $request->user_id,
            'job_id' => $request->job_id,
            'balance' =>$job->fee,
            'methods' =>  $methods,
            'transation_number' =>$this->generateNumberCode(),
            'marchent_name' =>$user->name,
        ]);

        $payments->workerPayments()->create([
            'user_id' => $user->id,
        ]);
        return $this->apiResponse(201, 'Applied Done');



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkerPayment  $workerPayment
     * @return \Illuminate\Http\Response
     */
    public function show(WorkerPayment $workerPayment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkerPayment  $workerPayment
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkerPayment $workerPayment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkerPayment  $workerPayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkerPayment $workerPayment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkerPayment  $workerPayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkerPayment $workerPayment)
    {
        //
    }
    public function generateNumberCode()
    {
        $transation_number = null;
        do {
            $transation_number =   'A'.(random_int(100000, 999999));
        } while (Payment::where("transation_number", "=", $transation_number)->first());
        return $transation_number;
    }

}
