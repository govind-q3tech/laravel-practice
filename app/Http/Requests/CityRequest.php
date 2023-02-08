<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Http\Request;

class CityRequest extends FormRequest
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
            'title' => 'required|regex:/^[\pL\s\-]+$/u|unique:cities,title',
            // 'slug' => 'required|unique:cities,slug',

        ];
        if ($request->isMethod("PATCH") && is_numeric($request->segment(3))) {
            $rules['title'] = 'required|regex:/^[\pL\s\-]+$/u|unique:cities,title,' . $request->segment(3);
        }
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
            'title.required'  => 'Please enter Name!',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Illuminate\Support\Facades\Session::flash('ValidatorError', 'Please check the required fields and complete them.');
        return parent::failedValidation($validator);
    }
}
