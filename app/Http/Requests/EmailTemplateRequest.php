<?php

namespace App\Http\Requests;

use \Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class EmailTemplateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'email_hook_id' => 'required|unique:email_templates,email_hook_id,' . $request->segment(3),
            'subject' => 'required|min:5',
            'description' => 'required|min:5',
            'email_preference_id' => 'required',
            'status' => 'required',
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
            'email_hook_id.required' => 'Please choose email hook/slug!',
            'email_hook_id.unique' => 'Each email template must have a unique hook slug! this hook template already created!!',
            'subject.required'  => 'Please enter email subject!',
            'subject.min'  => 'Subject must be at least 5 characters long!',
            'description.required'  => 'Please enter email description!',
            'description.min'  => 'Description must be at least 5 characters long!',
            'email_preference_id.required' => 'Please choose email template!',
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
