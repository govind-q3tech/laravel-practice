<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Http\Request;

class PlanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $rules =    [
            'title' => 'required|regex:/^[a-zA-Z0-9\s]+$/|min:2|max:200',
            'ordering' => 'required|numeric',

        ];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'The title is required field.',
            'title.min'  => 'Title must be at least 2 characters long!',
            'description.required'  => 'Please enter description!',
            'description.min'  => 'Plan description must be at least 50 characters long!',
        ];
    }
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
