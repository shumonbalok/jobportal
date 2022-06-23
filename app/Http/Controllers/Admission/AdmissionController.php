<?php

namespace App\Http\Controllers\Admission;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdmissionRequest;
use App\Http\Resources\AdmissionResource;
use App\Http\Resources\UserAdmissionResorce;
use App\Http\Resources\UserResorce;
use App\Models\Address;
use App\Models\Admission;
use App\Models\Graduate;
use App\Models\UserAdmission;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Ramsey\Collection\CollectionInterface;

class AdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admissions = Admission::with('university', 'unit', 'group' ,'admissionQuotas')->when(request()->has('search'), function ($query) {
            $query->where('name', 'LIKE', request()->get('search'));
        })->paginate(10);
        return $this->apiResponseResourceCollection(201, 'University list', AdmissionResource::collection($admissions));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdmissionRequest $request)
    {
        $admission = $request->validated();
        $admission['application_start'] = Carbon::parse($request->application_start)->format('Y-m-d');
        $admission['application_end'] = Carbon::parse($request->application_end)->format('Y-m-d');
        $admission['exam_time'] = Carbon::parse($request->exam_time)->format('Y-m-d');
        $admission =  Admission::create($admission);
        if($request->quota_id != null){
            foreach($request->quota_id as $value){
                $admission ->admissionQuotas()->attach([
                    'quota_id' => $value,
                ]);
            }
        }

        return $this->apiResponse(200, 'Admission Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Admission  $admission
     * @return \Illuminate\Http\Response
     */
    public function show(Admission $admission)
    {
        $admission->load('university', 'unit', 'group');
        return $this->apiResponse(200, 'Admission Details', AdmissionResource::make($admission));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Admission  $admission
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Admission  $admission
     * @return \Illuminate\Http\Response
     */
    public function update(AdmissionRequest $request, Admission $admission)
    {
        // dd($request->all());
        $admissions = $request->validated();
        // dd($admissions);
        $admissions['application_start'] = Carbon::parse($request->application_start)->format('Y-m-d');
        $admissions['application_end'] = Carbon::parse($request->application_end)->format('Y-m-d');
        $admissions['exam_time'] = Carbon::parse($request->exam_time)->format('Y-m-d');
        $admission->update($admissions);
        if($request->file)
        {
            $fileName = Rand() . '.' . $request->file('file')->getClientOriginalExtension();

            $file = $request->file('file')->storeAs('admission', $fileName, 'public');
            $input['file'] = $file;
            $admission->update($input);
            return $this->apiResponse(200, 'File Uploaded Successfull');
        }
        return $this->apiResponse(200, 'Admission Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Admission  $admission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admission $admission)
    {
        $admission->delete();
        return $this->apiResponse(200, 'Admission Deleted Successfully');
    }
    public function statusUpdate($id)
    {
        $admission = Admission::findOrFail($id);
        if ($admission->status == Admission::ACTIVE)
            $admission->status = Admission::INACTIVE;
        else
            $admission->status = Admission::ACTIVE;
        $admission->update();

        return $this->apiResponse(200, 'Admission Status Changed Successfully');
    }
    public function fileUpload(Request $request, $id)
    {
        $admission = Admission::findOrFail($id);
        $fileName = Rand() . '.' . $request->file('file')->getClientOriginalExtension();

        $file = $request->file('file')->storeAs('admission', $fileName, 'public');
        $input['file'] = $file;
        $admission->update($input);
        return $this->apiResponse(200, 'File Uploaded Successfull');
    }
    //  public function forceDelete($id)
    // {
    //     $admission= Admission::withTrashed()->find($id);
    //     $admission->forceDelete();
    //     return $this->apiResponse(201, 'Admission  Deleted Successfully');
    // }
    public function list()
    {
        $admissions = Admission::with('university', 'unit', 'group')->where('status', Admission::ACTIVE)->paginate(10);
        return $this->apiResponseResourceCollection(201, 'University list', AdmissionResource::collection($admissions));
    }
    public function userIndex()
    {
       
        $group = null;

        foreach (auth()->user()->graduates->load('examination') as $exam) {
            $group = $exam->group_id;
        }
        $admissions = Admission::with('university', 'unit', 'group' , 'admissionQuotas')->where('status', Admission::ACTIVE)->where('group_id', $group)->paginate(10);
        
        return $this->apiResponseResourceCollection(201, 'University list', AdmissionResource::collection($admissions));
    }
    public function eligibleUsers(Admission $admission)
    {
        $group_id = $admission->group_id;
        $list = [];
        $users = Graduate::where([['examination_id', 3], ['group_id', $group_id]])->with('users')->get();
        foreach ($users as $key => $user) {
            $gpa = null;
            $min_gpa = null;
            $user->users->load('graduates');
            foreach ($user->users->graduates as $graduate) {
                $graduate->load('examination', 'group');
                if ($graduate->examination->name == 'SSC' || $graduate->examination->name == 'HSC') {
                    if ($graduate->examination->name == 'HSC') {
                        $min_gpa = $graduate->result;
                    }
                    $gpa += $graduate->result;
                }
            }

            if ($admission->min_gpa && $admission->min_gpa <= $min_gpa && $admission->min_gpa_total && $admission->min_gpa_total <= $gpa) {

                $check=UserAdmission::where('admission_id',$admission->id)->where('user_id', $user->users->id)->first();

                if($check==null)
                $list[$key] = $user->users->load('basicInfo', 'experience', 'skill', 'address', 'graduates', 'higherGraduates', 'allphoto', 'allphotosub');
            }
        }
        $data = $this->paginate($list);
        return $this->apiResponseResourceCollection(200, 'Eligible Users List', UserResorce::collection($data));
    }
    public function nonApplied()
    {
         if(auth()->user()->profile_strength>30){
        $admissions = Admission::where('status', Admission::ACTIVE)->with('group')->get();
        $applied = auth()->user()->userAdmission()->get();
        $list[] = null;
        $ok = null;
        $gpa = null;
        $min_gpa = null;
        $group_id=null;
        foreach (auth()->user()->graduates as $graduate) {
            $graduate->load('examination', 'group');
            if ($graduate->examination->name == 'SSC' || $graduate->examination->name == 'HSC') {
                if ($graduate->examination->name == 'HSC') {
                    $min_gpa = $graduate->result;
                    $group_id=$graduate->group_id;
                }
                $gpa += $graduate->result;
            }
        }
        foreach ($admissions as $key => $admission) {
            $check=UserAdmission::where('admission_id',$admission->id)->where('user_id', auth()->user()->id)->first();
           
            if($check)
            {
               $admissions = $admissions->forget($key);
            }
            
            if($admission->group_id && $admission->group_id!=$group_id)
            $admissions = $admissions->forget($key);
             if( $admission->min_gpa > $min_gpa && $admission->min_gpa_total > $gpa) {
                $admissions = $admissions->forget($key);

            }
            foreach ($applied as $apply) {
                if ($apply->admission_id == $admission->id)
                     $admissions = $admissions->forget($key);
                   }
        }
         }
        else
        $admissions = Admission::where('status', Admission::ACTIVE)->paginate(10);
        return $this->apiResponseResourceCollection(200, 'All Jobs', AdmissionResource::collection($admissions));
    }
    public function paginate($items, $perPage = 4, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function admissionFillter(Request $request){

        $amissionFillter = Admission::where(['unit_id' => $request->unit_id])->orWhere(['university_id' =>$request->university_id])->orWhere(['group_id' => $request->group_id])
           ->get();
            return $this->apiResponseResourceCollection(200, 'Job Fillter', AdmissionResource::collection($amissionFillter));
    }

    public function admisionHistoryFillter(Request $request){
        // $request->validate([
        //     'unit_id'  => ['required'],
        //     'university_id'  => ['required'],
        //     'group_id'  => ['required'],
        //     'application_start'  => ['required'],
        //     'application_end'  => ['required'],
        // ]);

        $amissionFillter = UserAdmission::with([
            'admission' =>function($q){
                $q->where('unit_id',  request()->input('unit_id'));
                $q->orWhere('university_id',request()->input('university_id'));
                $q->orWhere('group_id',request()->input('group_id'));
            }
            ])->where('user_id', auth()->id())->get();
            return $this->apiResponseResourceCollection(200, 'Job Fillter', UserAdmissionResorce::collection($amissionFillter));
    }
}
