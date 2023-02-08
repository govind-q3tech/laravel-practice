<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Http\Request;

class VoucherRequest extends FormRequest
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
            'title' => 'required|min:2|max:100|unique:vouchers',
            'vouchercode' => 'required|min:2|max:100|unique:vouchers',
            'value' => 'required|min:1|max:100',
            'from_date' => 'required',
            'to_date' => 'required',
            
        ];
        if($request->isMethod("PATCH") && is_numeric($request->segment(3))){
            $rules['vouchercode'] = 'required|min:2|max:100|unique:vouchers,vouchercode,'.$request->segment(3);
            $rules['title'] = 'required|min:2|max:100|unique:vouchers,title,'.$request->segment(3);
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
            'title.required'  => 'Please enter title!',
            'title.min'  => 'Page Title must be at least 2 characters long!',
            'voucher.required' => 'Please enter page slug!',
            'slug.unique' => 'Each cms pages must have a unique slug! this page slug already created!',

        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Illuminate\Support\Facades\Session::flash('ValidatorError', 'Please check the required fields and complete them.');
        return parent::failedValidation($validator);
    }



}