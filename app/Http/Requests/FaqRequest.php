<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Http\Request;

class FaqRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $rules  =   [
            'question' => 'required|min:2',
            'answer' => 'required|min:2',
            'ordering' => 'required|integer',
           
            
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
            'question.required'  => 'Please enter question!',
            'question.min'  => 'question must be at least 2 characters long!',
            'answer.required'  => 'Please enter answer!',
            'answer.min'  => 'Answer must be at least 2 characters long!',            
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Illuminate\Support\Facades\Session::flash('ValidatorError', 'Please check the required fields and complete them.');
        return parent::failedValidation($validator);
    }



}