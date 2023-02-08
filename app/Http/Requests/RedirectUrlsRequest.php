<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class RedirectUrlsRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array 
     */
    public function rules(Request $request) { 

         $rules = []; 
        if(!$request->isMethod('PATCH')){
           $rules['old_url'] = 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/|unique:redirect_urls,old_url';
            $rules['new_url'] = 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/|unique:redirect_urls,new_url';
        } 
        return $rules;

        
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages() {
        return [
            'old_url.required' => 'Please enter old url.',
            'old_url.regex' => 'New url have to be a valid url (try putting) http:// or https:// or another prefix at the beginning', 
            'new_url.required'   => 'Please enter new url.', 
             'new_url.regex' => 'New url have to be a valid url (try putting) http:// or https:// or another prefix at the beginning', 

        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

}
