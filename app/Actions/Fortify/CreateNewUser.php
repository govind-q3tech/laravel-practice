<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use App\Models\Listing;
use App\Models\Opening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \App\Rules\MinWordsRule;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    /**
     *  Image upload path.
     *
     * @var string
     */
    protected $image_upload_path;

    /**
     * Storage Class Object.
     *
     * @var \Illuminate\Support\Facades\Storage
     */
    protected $storage;

    /**
     * Constructor.
     */
     public function __construct()
    {
        $this->image_upload_path = 'listing/profile' . DIRECTORY_SEPARATOR;
        $this->storage = Storage::disk('public');
    }
    public function create(array $input)
    { 
        // , 'not_throw_away' 
        $request = app('request');

        $rules = [
        'first_name' => ['required', 'string', 'max:255'],
        'last_name' => ['nullable', 'string', 'max:255'],
        'email'  => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => $this->passwordRules(),
        'terms'    => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['required', 'accepted'] : '',
        'phone' => ['required', 'numeric', 'min:10', 'regex:/2547[0-9]{6}/', 'unique:users,phone']
        ]; 
        $customMessages = [];

        Validator::make($input,$rules, $customMessages)->validate();
        
        try
        {
            
            $user = User::create([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'phone' => $input['phone'],
            'email' => $input['email'],
            'address' => $input['address'],
            'status' => 1,
            'password' => Hash::make($input['password']),
            ]);

            // $requestData = $input;
            // $requestData['user_id'] = $user->id;
            // $requestData['short_description'] = $requestData['title'] ;

            // if ($request->hasFile('image')) {
            //     $image_name = $this->uploadImage(null, $request->file('image'));
            //     $requestData['image'] = $image_name;
            // }
            // $listing = Listing::create($requestData);
            
            // if(isset($listing->id)){
            //     $user_update = User::find($user->id);
            //     $user_update->plan_id = $input['plan'];
            //     $user_update->save();
            // }
             
            
            // add gallery Data

            // app('App\Http\Controllers\GalleriesController')->uploadData($request, $listing->id);
            // $listing->locations()->sync($requestData['locations']);
            // $listing->categories()->sync($requestData['categories']);
                       
            // $openings = [];
            // foreach($requestData['openings'] as $opening){
            // $opening['status'] = 1;
            // $openings[] = new  Opening($opening);
            // }
            // $listing->openings()->saveMany($openings);

            
        }catch (\Illuminate\Database\QueryException $e) {

             dd($e);
        } 
            
       return $user;   
    }
    /*
     * upload image
     */
    public function uploadImage($model = null, $image)
    {
        if ($model != null) {
            if ($model->image != null) {
                $this->removeBannerImage($model);
            }
        }

        $path = $this->image_upload_path;

        $image_name = time() . $image->getClientOriginalName();

        // dd($path);

        $this->storage->put($this->image_upload_path . $image_name, file_get_contents($image->getRealPath()));

        return $image_name;
    }
}
