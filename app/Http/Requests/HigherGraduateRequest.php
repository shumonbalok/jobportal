<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HigherGraduateRequest extends FormRequest
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
            'name'  => ['required', 'string' ],
            'subject_id' =>'required|exists:subjects,id',
            'universities_id' =>'required|exists:universities,id',
            'roll_no' => 'required|string',
            'registration_no' => 'required|string',
            'result' => 'required|string',
            'passing_year_id' => 'required|exists:passing_years,id',
            'course_duration_id' => 'required|exists:course_durations,id',
            'type' => 'required',
        ];
    }
}
