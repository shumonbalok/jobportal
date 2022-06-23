<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
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
            'min_age' => [Rule::requiredIf(!$this->max_age)],
            'max_age' => [Rule::requiredIf(!$this->min_age)],
            'sit' => ['required', 'string'],
            'post_id'       =>['required','exists:posts,id'],
            'department_id' => ['required', 'exists:departments,id'],
            'examination_id' => ['nullable'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'grade_id' => ['nullable', 'exists:grades,id'],
            'group_id'  => ['nullable', 'exists:groups,id'],
            'skill'  => ['nullable', 'string'],
            'experience' => ['nullable', 'string'],
            'experience_details' => ['nullable', 'string'],
            'fee'  => ['required',],
            'service_fee'  => ['required',],
            'start_time'  => ['required', ],
            'end_time'  => ['required', ],
            'type' => ['nullable'],
            'sharing_url'=>['string'],
        ];
    }
}
