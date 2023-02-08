<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Http\Request;

class EmailHookRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        $rules  =  [
            'title' => 'required|min:5|regex:/^[\pL\s\-]+$/u',
            'slug' => 'required|alpha_dash|unique:email_hooks,slug',
            'description' => 'required|min:50',
        ];
        if ($request->isMethod("PATCH") && is_numeric($request->segment(3))) {
            $rules['slug'] = 'required|alpha_dash|unique:email_hooks,slug,' . $request->segment(3);
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
            'title.required' => 'Please enter an email hook title!',
            'title.min'  => 'Email Hook must be at least 5 characters long!',
            'description.required'  => 'Please enter description!',
            'description.min'  => 'Hook description must be at least 50 characters long!',
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
