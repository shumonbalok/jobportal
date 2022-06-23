<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BasicInfoRequest extends FormRequest
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
            'user_id'       =>'nullable|exists:users,id',
            'full_name'     => 'required|string|regex:/^[\pL\s\-]+$/u',
            'father_name'   => 'required|string|regex:/^[\pL\s\-]+$/u',
            'mother_name'   => 'required|string|regex:/^[\pL\s\-]+$/u',
            'birth_date'    =>'required|string',
            'gender'        =>'required',
            'height'        =>'required',
            'religion'      =>'required',
            'marital_status'=>'required',
            'nid'           =>'required|string',
            'mobile'        => 'required|size:11|regex:/(01)[0-9]{9}/',
            'email'         =>'required|string',
            'quota_id'      =>'nullable',
            'passport_number'   => 'nullable'
        ];
    }
    public function messages()
    {
        return [
            'mobile.regex' => 'Invalide Phone Number Type',
            'full_name.regex'     => 'Name must contain letters only',
            'father_name.regex'     => 'Name must contain letters only',
            'mother_name.regex'     => 'Name must contain letters only'
        ];
    }
}
