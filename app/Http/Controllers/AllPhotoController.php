<?php

namespace App\Http\Controllers;

use App\Http\Requests\AllPhotoRequest;
use App\Models\AllPhoto;
use App\Models\AllPhotoSub;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AllPhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $photos = auth()->user()->with(['allphoto' , 'allphotosub'])->first();
        // dd($photos);
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
    public function store(Request $request)
    {
        // $request->validate([
        //     'pp_photos' => 'required',
        //     'signature_photos' => 'required'
        // ]);

        if ($request->user_id) {
            $user = User::with('allphoto')->find($request->user_id);
        } else {
            $user = auth()->user()->load('allphoto');
        }
        if($user->allphoto == null)
        {
            $user->allphoto()->create();
        }
        $pp_photos = $user->allphoto?->pp_photos;
        $signature_photos = $user->allphoto?->signature_photos;
        $nid_photos = $user->allphoto?->nid_photos;
        $passport_photos = $user->allphoto?->passport_photos;
        $birth_certificate_photos = $user->allphoto?->birth_certificate_photos;

        if ($request->hasFile('pp_photos')) {

            if (File::exists('storage/jobProfile/' . $user->allphoto?->pp_photos)) {
                File::delete('storage/jobProfile/' . $user->allphoto?->pp_photos);
            }
            $fileName = Rand() . '.' . $request->file('pp_photos')->getClientOriginalExtension();

            $pp_photos = $request->file('pp_photos')->storeAs('jobProfile', $fileName, 'public');
            if ($user->allphoto?->pp_photos == null)
            {
                $user->increment('profile_strength', 10);
                $user->allphoto()->update(['pp_photos' => $pp_photos]);
            }
            else
                $user->allphoto()->update(['pp_photos' => $pp_photos]);
        }

        if ($request->hasFile('signature_photos')) {
            if (File::exists('storage/jobProfile/' . $user->allphoto?->signature_photos)) {
                File::delete('storage/jobProfile/' . $user->allphoto?->signature_photos);
            }
            $fileName = Rand() . '.' . $request->file('signature_photos')->getClientOriginalExtension();
            $signature_photos = $request->file('signature_photos')->storeAs('jobProfile', $fileName, 'public');
            if ($user->allphoto?->signature_photos == null){
                $user->increment('profile_strength', 10);
                $user->allphoto()->update(['signature_photos' => $signature_photos]);
                } else
                    $user->allphoto()->update(['signature_photos' => $signature_photos]);
        }

        if ($request->hasFile('nid_photos')) {
            if (File::exists('storage/jobProfile/' . $user->allphoto?->nid_photos)) {
                File::delete('storage/jobProfile/' . $user->allphoto?->nid_photos);
            }
            $fileName = Rand() . '.' . $request->file('nid_photos')->getClientOriginalExtension();

            $nid_photos = $request->file('nid_photos')->storeAs('jobProfile', $fileName, 'public');
            if ($user->allphoto?->nid_photos == null) {
                $user->increment('profile_strength', 5);
                $user->allphoto()->update(['nid_photos' => $nid_photos]);
            } else
                $user->allphoto()->update(['nid_photos' => $nid_photos]);
        }

        if ($request->hasFile('passport_photos')) {
            if (File::exists('storage/jobProfile/' . $user->allphoto?->passport_photos)) {
                File::delete('storage/jobProfile/' . $user->allphoto?->passport_photos);
            }
            $fileName = Rand() . '.' . $request->file('passport_photos')->getClientOriginalExtension();

            $passport_photos = $request->file('passport_photos')->storeAs('jobProfile', $fileName, 'public');
            if ($user->allphoto?->passport_photos == null) {
                $user->increment('profile_strength', 5);
                $user->allphoto()->update(['passport_photos' => $passport_photos]);
            } else
                $user->allphoto()->update(['passport_photos' => $passport_photos]);
        }

        if ($request->hasFile('birth_certificate_photos')) {
            if (File::exists('storage/jobProfile/' . $user->allphoto?->birth_certificate_photos)) {
                File::delete('storage/jobProfile/' . $user->allphoto?->birth_certificate_photos);
            }
            $fileName = Rand() . '.' . $request->file('birth_certificate_photos')->getClientOriginalExtension();

            $birth_certificate_photos = $request->file('birth_certificate_photos')->storeAs('jobProfile', $fileName, 'public');
            if ($user->allphoto?->birth_certificate_photos == null) {

                $user->allphoto()->update(['birth_certificate_photos' => $birth_certificate_photos]);
            } else
                $user->allphoto()->update(['birth_certificate_photos' => $birth_certificate_photos]);
        }


            return response()->json(
                [
                    'profile_strength' => $user->profile_strength,
                    'message' => 'Photo  create Successfully',
                ],
                201
            );

    }




    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AllPhoto  $allPhoto
     * @return \Illuminate\Http\Response
     */
    public function show(AllPhoto $allPhoto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AllPhoto  $allPhoto
     * @return \Illuminate\Http\Response
     */
    public function edit(AllPhoto $allPhoto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AllPhoto  $allPhoto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AllPhoto $allPhoto)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AllPhoto  $allPhoto
     * @return \Illuminate\Http\Response
     */
    public function destroy(AllPhoto $allPhoto)
    {
    }
}
