<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use App\Rules\CheckPhone;
use Auth;

class ProfileRequest extends FormRequest
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
        $rule['first_name']    = 'required|string|min:2|max:100';
        $rule['last_name']     = 'nullable|string|min:2|max:100';
        $rule['address']       = 'required|string';
        $rule['city_id']       = 'required';
        $rule['area_id']       = 'required';
        $rule['facebook_url']   = 'required|url';
        $rule['twitter_url']   = 'required|url';
        $rule['instagram_url']   = 'required|url';
        $rule['youtube_url']   = 'required|url';
        $user = Auth::user();
        if ($request->isMethod("PATCH") && !empty($user)) {
            $rule['email']     = 'required|email|unique:users,email,' . $user->id;
            // $rule['phone']     = 'required|numeric|regex:/254[0-9]{6}/|unique:users,phone,' . $user->id;
            $rule['phone'] = ['required', 'numeric', 'min:10', 'unique:users,phone,' . $user->id, new CheckPhone];
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
            'phone.unique'          => 'This phone have already registered. please use another phone.',
            'city_id.required'      => 'The city is required field',
            'area_id.required'      => 'The area is required field',
            'location_id.required'  => 'The location is required field',
        ];
    }
}
