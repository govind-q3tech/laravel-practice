<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckPhone;

class AdminUserRequest extends FormRequest
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
        $rule['first_name']     = 'required|regex:/^[\pL\s\-]+$/u|min:2|max:100';
        // $rule['email']          = 'required|email|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix|unique:admin_users,email,' . $this->route('admin_user.id');
        $rule['email']          = 'required|email:rfc,dns|unique:admin_users,email,' . $this->route('admin_user.id');
        $rule['dob']            = 'nullable|date';
        // $rule['mobile']         = 'nullable|digits:11';
        $rule['mobile']         = ['required', 'numeric', 'min:10'];
        $rule['role_id']        = 'required';
        if ($this->has('password')) {
            $rule['password']   = 'required|sometimes|min:6|confirmed';
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
            'first_name.required'   => 'The name is required field.',
            'email.required'        => 'The email ID is required field',
            'email.not_throw_away'  => 'The email domain is invalid.',
            'email.unique'          => 'This email id have already created. please use another email ID.',
            'password.required'     => 'The password is required field.',
            'dob.required'          => 'The date of birth is required field.',
            'role_id.required'      => 'The privilege is required field.',
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
