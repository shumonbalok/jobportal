<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionRequest extends FormRequest
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
            'name'              =>'required|string',
            'unit_id'           =>'required|exists:units,id',
            'university_id'     =>'required|exists:universities,id',
            'application_start' =>'required',
            'application_end'   =>'required',
            'exam_time'         =>'nullable|string',
            'application_fee'   =>'required|numeric',
            'min_gpa'           =>'nullable|string',
            'min_gpa_total'     =>'nullable|string',
            'group_id'          =>'nullable|exists:groups,id',
            'qouta'             =>'nullable|string',
            'seat'              =>'nullable'
        ];
    }
}
