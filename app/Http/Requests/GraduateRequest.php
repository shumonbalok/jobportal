<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GraduateRequest extends FormRequest
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
            'name'  => ['required', 'string'],
            'examination_id' => ['required' ,'exists:examinations,id'],
            'board_id' => ['required' ,'exists:boards,id'],
            'roll_no' => ['required'],
            'registration_no' => ['required'],
            'result' => ['required'],
            'passing_year_id' => ['required' ,'exists:passing_years,id'],
            'group_id'      =>['nullable','exists:groups,id']
        ];
    }
}
