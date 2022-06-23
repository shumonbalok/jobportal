<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashBoardController extends Controller
{
   public function allCount(){
       $userCount = DB::table('users')->where('type', 1)->count();
       $jobCount = DB::table('jobs')->count();
       $appliedJobs = DB::table('applied_jobs')->count();
       $userAdmmission = DB::table('user_admissions')->count();
       $marchent = User::whereHas('roles', function ($query) {
           $query->where('roles.name', 'Merchant');
       })->count();
       $worker = User::whereHas('roles', function ($query) {
           $query->where('roles.name', 'worker');
       })->count();
       $balance = auth()->user()->balance;
       $totalCommission = auth()->user()->profit;

       return response()->json([
           'userCount' =>  $userCount,
           'jobCount' =>  $jobCount,
           'appliedJobs' =>  $appliedJobs,
           'userAdmmission' =>  $userAdmmission,
           'marchent' =>  $marchent,
           'worker' =>  $worker,
           'balance' =>  $balance,
           'totalCommission' =>  $totalCommission,
       ]);
   }

}
