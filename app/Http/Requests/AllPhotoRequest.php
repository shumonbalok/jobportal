<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AllPhotoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            'user_id'                   => 'required|exists:users,id',
            'pp_photos'                 => 'required',
            'signature_photos'          => 'required',
            'nid_photos'                => 'required',
            'passport_photos'           => 'required',
            'birth_certificate_photos'  => 'required',
            'ssc_certificate_photos'    => 'required',
            'hsc_certificate_photos'    => 'required',
            'photos_name'               => 'required|string',
            'certificate_photos_sub'    => 'required'
        ];
    }
}
