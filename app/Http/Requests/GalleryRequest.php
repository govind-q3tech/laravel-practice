<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Http\Request;

class GalleryRequest extends FormRequest
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
        if($request->segment(3)){
            $rules  =   [
                'gallery_image.0.title' => 'required|max:100',
                'gallery_image.0.image_name' => 'max:5120',
                'gallery_image.0.image_link' => 'required|url',
            ];
        } else {

            $rules  =   [
                'gallery_image.0.title' => 'required|max:100',
                'gallery_image.0.image_name' => 'required|max:5120',
                'gallery_image.0.image_link' => 'required|url',
                // 'banner_images.*.image.required' => 'Please select image',
            ];
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
            'gallery_image.0.image_name.required' => 'Please select image',
            // 'gallery_image.*.image_name.required' => 'Please select image',
            'gallery_image.0.image_name.max' => 'The image must not be greater then 2MB.',
            'gallery_image.0.image_link.required' => 'The image link is required.',
            'gallery_image.0.image_link.url' => 'The image link is not valid.',
            'gallery_image.0.title.required' => 'The title is required.',
            'gallery_image.0.title.max' => 'The title cannot be greater than 100 characters.',

            // 'gallery_image.*.image_name.max' => 'The image must not be greater then 2MB.',
            // 'gallery_image.*.image_name.mimes'  => 'The banner image must be a file of type: jpeg, png, jpg.!',
        ];
    }



    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {

        \Illuminate\Support\Facades\Session::flash('ValidatorError', 'Please check the required fields and complete them.');
        return parent::failedValidation($validator);
    }
}
