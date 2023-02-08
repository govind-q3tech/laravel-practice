<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Http\Request;

class LocationRequest extends FormRequest
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
            'area_id' => 'required',
            'title' => 'required|regex:/^[\pL\s\-]+$/u|unique:locations,title',
            //'slug' => 'required|unique:locations,slug',


        ];
        if ($request->isMethod("PATCH") && is_numeric($request->segment(3))) {
            $rules['title'] = 'required|regex:/^[\pL\s\-]+$/u|unique:locations,title,' . $request->segment(3);
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
            'area_id.required'  => 'Please select area!',
            'title.required'  => 'Please enter title!',
            'title.min'  => 'Page Title must be at least 2 characters long!',
            'slug.required' => 'Please enter page slug!',
            'slug.unique' => 'Each location must have a unique slug! this page slug already created!',
            'lat.required'  => 'Please enter latitude!',
            'lng.required'  => 'Please enter longitude!',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Illuminate\Support\Facades\Session::flash('ValidatorError', 'Please check the required fields and complete them.');
        return parent::failedValidation($validator);
    }
}
