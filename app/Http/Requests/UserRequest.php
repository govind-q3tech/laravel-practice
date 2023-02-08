<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Rules\CheckPhone;
class UserRequest extends FormRequest
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
        $rule['first_name']       = 'required|regex:/^[\pL\s\-]+$/u|min:2|max:100';
        $rule['last_name']       = 'nullable|regex:/^[\pL\s\-]+$/u|min:2|max:100';
        $rule['email']      = 'required|email:rfc,dns|unique:users,email';
        // $rule['email']      = 'required|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users,email';
        if($request->type == 'advertisers'){
            $rule['store_name']       = 'required|min:2|max:100|regex:/^[\pL\s\-]+$/u';
            $rule['description']       = 'required|min:2|max:100|regex:/^[\pL\s\-]+$/u';
        }
        // $rule['phone']     = 'required|numeric|min:10|regex:/2547[0-9]{6}/|unique:users,phone';

        if ($request->isMethod("PATCH") && is_numeric($request->segment(3))) {
            // $rule['email']      = 'required|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:users,email,' . $request->segment(3);
            $rule['email']      = 'email:rfc,dns|unique:users,email,' . $request->segment(3);
            // $rule['phone']      = 'required|numeric|regex:/2547[0-9]{6}/|unique:users,phone,' . $request->segment(3);
            $rule['phone']      = ['numeric', 'min:10', 'unique:users,phone,' . $request->segment(3), new CheckPhone];
        }else{
            $rule['phone'] = ['required', 'numeric', 'min:10', 'unique:users,phone', new CheckPhone];
        }
        return $rule;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required'   => 'The Name is required field.',
            'email.required'        => 'The Email ID is required field',
            'email.unique'          => 'This Email ID have already created. please use another email ID.',
            'phone.required'        => 'The phone is required field',
            'phone.regex'           => 'The phone format is not valid use valid number 2547XXXXXXXXX.',
            'phone.unique'          => 'This phone have already registered. please use another phone.',
            'password.required'     => 'The Password is required field.',
            'password.regex'        => 'The Password must be at least 6 characters long, contain at least one number, one special character and have a mixture of uppercase and lowercase letters.',
            'dob.required'          => 'The Date of birth is required field.',
            'role_id.required'      => 'The Privilege is required field.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return mixed
     */

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Illuminate\Support\Facades\Session::flash('ValidatorError', 'Please check the required fields and complete.');
        return parent::failedValidation($validator);
    }
}
