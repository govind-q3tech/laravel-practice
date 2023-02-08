<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use \App\Rules\MinWordsRule;

class ListingRequest extends FormRequest
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

        $planFeatures = \App\Helpers\Helper::getPlanData();
        if(isset($planFeatures[1]->value) && $planFeatures[1]->value > 0){
            // for front user;
            $imageCount = $planFeatures[1]->value;
        }else{
            //for admin
            $imageCount = 120;

        }
        
        $rules  =   [
            'title' => 'required|min:2|max:100|unique:listings',
            
            'description' => new MinWordsRule(150),
            'email' => 'required|email',
            'location' => 'required|min:5',
         //   'website' => ['required','regex:/^(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|localhost|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$/'],
            'lat' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'],
            'lng' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'image' => 'required|image|mimes:jpeg,png,jpg|max:102401|dimensions:min_width=300,min_height=300',
            'locations' => 'required',
            'categories' => 'required',
            'phone' => 'required',
            'sort_order' => 'numeric',
            'images' => 'max:'.$imageCount,
            
        ];
        if($request->segment(1) == 'admin'){
            $rules['user_id'] = 'required|numeric';
        }
        
        if($request->segment(1) == 'admin' && $request->isMethod("PATCH") && is_numeric($request->segment(3))){
            $rules['title'] = 'required|min:2|max:100|unique:locations,title,'.$request->segment(3);
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:102401|dimensions:min_width=300,min_height=300';
        }elseif($request->segment(1) != 'admin' && $request->isMethod("PATCH") && is_numeric($request->segment(2))){
            $images = \App\Helpers\Helper::getListingImages($request->segment(2));
            $allowImage = 0;
            if($images < $planFeatures[1]->value ){
                $allowImage = $planFeatures[1]->value - $images;
            }
            $rules['title'] = 'required|min:2|max:100|unique:locations,title,'.$request->segment(2);
            $rules['image'] = 'nullable|image|mimes:jpeg,png,jpg|max:102401|dimensions:min_width=300,min_height=300';
            $rules['images'] = 'max:'.$allowImage;
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
            'title.min'  => 'Title must be at least 2 characters long!',
            'image.image' => 'Image field must be image',
            'image.max' => 'Image size must be max 10mb',
            'location.required' => 'Address is required.',
          //  'image.dimensions' => 'Image should be square size.',
           
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        \Illuminate\Support\Facades\Session::flash('ValidatorError', 'Please check the required fields and complete them.');
        return parent::failedValidation($validator);
    }
}