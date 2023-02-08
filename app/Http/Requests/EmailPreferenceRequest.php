<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailPreferenceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:5',
            'layout_html' => 'required|min:150',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'Please enter layout title!',
            'title.min'  => 'Email Hook must be at least 5 characters long!',
            'layout_html.required'  => 'Please enter layout html!',
            'layout_html.min'  => 'Layout Html must be at least 50 characters long!',
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
