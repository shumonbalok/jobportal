<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id'               =>'nullable|exists:users,id',
            'care_of_present'       =>'required|string',
            'details_present'       =>'required|string',
            'district_id_present'   =>'required|exists:districts,id',
            'upazila_id_present'    =>'required|exists:upazilas,id',
            'post_office_id_present'=>'required|exists:post_offices,id',
            'postal_code_present'  =>'required|string',
            'same_as'               =>'required',
            'care_of_permanent'       =>'nullable|string',
            'details_permanent'       =>'nullable|string',
            'district_id_permanent'   =>'nullable',
            'upazila_id_permanent'    =>'nullable',
            'post_office_id_permanent'=>'nullable',
            'postal_code_permanent'   =>'nullable|string',
        ];
    }
}
