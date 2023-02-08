<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;
use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\Contact;
use App\Models\Discussion;
use App\Models\DiscussionMessage;
use App\Models\EventNotification;
use App\Models\Faq;
use App\Models\Membership\Subscription;
use App\Models\Page;
use App\Models\User;
//use Validator;
use App\Models\UserWishlist;
use App\Traits\ApiGlobalFunctions;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mail;

class UsersController extends Controller
{
    use ApiGlobalFunctions;

    public function sendOtpVerify(Request $request)
    {
        $data = [];
        switch ($request->action) {
            case "send_otp":
                $validator = Validator::make($request->all(), [
                    'mobile_number' => 'required|numeric|regex:/2547[0-9]{6}/|unique:users,phone',
                ]);
                if ($validator->fails()) {
                    $errors = $validator->messages();
                    $data['status'] = 0;
                    $data['message'] = $errors->first('mobile_number');
                    echo json_encode($data);
                    exit;
                }
                $mobile_number = $request->mobile_number;
                // $apiKey = urlencode('YOUR_API_KEY');
                // $Textlocal = new Textlocal(false, false, $apiKey);
                $numbers = array(
                    $mobile_number,
                );
                $sender = 'PHPPOT';
                //$otp = rand(100000, 999999);
                $otp = 123456;
                Session::put('mobile_number', $mobile_number);
                Session::put('session_otp', $otp);
                $message = "Your One Time Password is " . $otp;
                try {
                    //$response = $Textlocal->sendSms($numbers, $message, $sender);
                    //require_once ("verification-form.php");
                    $data['status'] = 1;
                    $data['message'] = "OTP send Successfully";
                    echo json_encode($data);
                } catch (Exception $e) {
                    $data['status'] = 0;
                    $data['message'] = 'Error: ' . $e->getMessage();
                    echo json_encode($data);
                }
                break;
            case "resend_otp":
                $validator = Validator::make($request->all(), [
                    'mobile_number' => 'required|numeric|regex:/2547[0-9]{6}/|unique:users,phone',
                ]);
                if ($validator->fails()) {
                    $errors = $validator->messages();
                    $data['status'] = 0;
                    $data['message'] = $errors->first('mobile_number');
                    echo json_encode($data);
                }
                $mobile_number = $request->mobile_number;
                // $apiKey = urlencode('YOUR_API_KEY');
                // $Textlocal = new Textlocal(false, false, $apiKey);
                $numbers = array(
                    $mobile_number,
                );
                $sender = 'PHPPOT';
                //$otp = rand(100000, 999999);
                $otp = 654321;
                Session::put('mobile_number', $mobile_number);
                Session::put('session_otp', $otp);
                $message = "Your One Time Password is " . $otp;
                try {
                    //$response = $Textlocal->sendSms($numbers, $message, $sender);
                    //require_once ("verification-form.php");
                    $data['status'] = 1;
                    $data['message'] = "OTP send Successfully";
                    echo json_encode($data);
                } catch (Exception $e) {
                    $data['status'] = 0;
                    $data['message'] = 'Error: ' . $e->getMessage();
                    echo json_encode($data);
                }
                break;
            case "verify_otp":
                $otp = $request->otp;
                //$request()->session()->get('session_otp')
                $value = Session::get('session_otp');
                // $value = 123456;
                if ($otp == $value) {
                    Session::put('OTPVerified', true);
                    // dd(Session::all());
                    //$request()->session()->forget('session_otp');
                    $data['status'] = 1;
                    $data['message'] = "Your mobile number is verified!";
                    echo json_encode($data);
                } else {
                    $data['status'] = 0;
                    $data['message'] = "Mobile number verification failed";
                    echo json_encode($data);
                }
                break;
        }
    }

    public function login(Request $request)
    {

        $input = $request->all();
        $data = [];
        try {
            $validator = Validator::make($input, ['email' => 'required', 'password' => 'required', 'device_type' => 'required']);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            } else {
                $query = User::query();
                $email_exist = $query->where('email', request('email'))->count();
                if ($email_exist == 0) {
                    return $this->sendError($this->messageDefault('invalid_login'), '', '200');
                } elseif (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
                    $user = Auth::user();
                    if (!$user->status) {
                        return $this->sendError($this->messageDefault('not_active') . 'support for assistance.', '', '200');
                    } elseif (!$user->email_verified_at) {
                        return $this->sendError($this->messageDefault('not_verified'), '', '200');
                    } else {
                        $input = $request->all();
                        $data = $user;
                        $data['device_id'] = isset($input['device_id']) ? str_replace('"', '', $input['device_id']) : $data['device_id'];
                        $data['device_type'] = isset($input['device_type']) ? $input['device_type'] : $data['device_type'];
                        $data['api_token'] = $user->createToken('kwaki')->plainTextToken;
                        User::where('id', $user->id)->update(['api_token' => $data['api_token'], 'device_id' => $data['device_id'], 'device_type' => $data['device_type']]);
                        $userData = User::where('id', $user->id)->first();
                        return $this->sendResponse($userData, $this->messageDefault('login_success'));
                    }
                } else {
                    return $this->sendError($this->messageDefault('invalid_login'), '', '200');
                }
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    public function register(Request $request)
    {
        $input = $request->all();
        // echo "<prE>"; print_r($input); die;
        try {
            // $user = new User();
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|min:3',
                //'last_name' => 'required|min:3',
                'email' => 'required|email|unique:users,email',
                //'phone' =>  'required|numeric|regex:/2547[0-9]{6}/|unique:users,phone',
                'phone' => 'required|numeric|unique:users,phone',
                'password' => 'required|min:6',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }
            $input['last_name'] = isset($input['last_name']) ? $input['last_name'] : "";
            //$otp = rand(100000, 999999);
            $otp = 123456;
            $data = DB::table('user_mobile_verifications')->insert(['mobile' => $input['phone'], 'otp' => $otp]);
            // $user->email = $input['email'];
            // $user->first_name = $input['first_name'] ;
            // $user->last_name = $input['last_name'] ;
            // $user->phone = $input['phone'];
            // $user->device_id = isset($input['device_id']) ? $input['device_id'] : "";
            // $user->device_type = isset($input['device_type']) ? $input['device_type'] : "";
            // $user->password = bcrypt($input['password']);
            // $user->verification_code = $otp;
            // $user->save();

            if ($data) {
                return $this->sendResponse($data, $this->messageDefault('signup_success'));
            } else {
                return $this->sendError($this->messageDefault('signup_error'), '', '200');
            }
        } catch (\Exception $e) {
            return $this->sendError($e, '', '200');
        }
    }

    public function verifyOtp(Request $request)
    {
        $input = $request->all();
        try {

            $validator = Validator::make($request->all(), [
                'otp' => 'required',
                'first_name' => 'required|min:3',
                //'last_name' => 'required|min:3',
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|numeric|unique:users,phone',
                'password' => 'required|min:6',
            ]);

            if ($validator->fails()) {

                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }

            $data = DB::table('user_mobile_verifications')->where(['mobile' => $request->phone, 'otp' => $request->otp])->latest()->first();

            if ($data) {

                if ($data->is_verified == 1) {
                    $user = (object) [];
                    return $this->sendResponse($user, $this->messageDefault('Your mobile number already Verified.'));
                } else {
                    DB::table('user_mobile_verifications')->where('id', $data->id)->update(['is_verified' => 1]);
                    $user = new User();
                    $user->email = $input['email'];
                    $user->first_name = $input['first_name'];
                    $user->last_name = isset($input['last_name']) ? $input['last_name'] : "";
                    $user->phone = $input['phone'];
                    $user->device_id = isset($input['device_id']) ? $input['device_id'] : "";
                    $user->device_type = isset($input['device_type']) ? $input['device_type'] : "";
                    $user->password = bcrypt($input['password']);
                    //$user->is_approved = 1;
                    $user->status = 1;
                    $user->save();
                    // $userData = user::where('id',$user->id)->first();
                    // event(new Registered($userData));
                    return $this->sendResponse($user, $this->messageDefault('Your mobile number Verified Successfully.'));
                }
            } else {

                return $this->sendError($this->messageDefault('This code is not valid'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    public function forgotPassword(Request $request)
    {
        $input = $request->all();
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
            ], [

                'email.required' => 'Please provide email id.',

            ]);
            if ($validator->fails()) {

                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }
            $query = User::where(['email' => $request->email]);
            if ($query->exists()) {
                $user = $query->first();
                if (!$user->status) {
                    return $this->sendError($this->messageDefault('not_activated'), '', '200');
                } elseif (!$user->email_verified_at) {
                    return $this->sendError($this->messageDefault('not_verified'), '', '200');
                }
                $code = rand(111111, 999999);
                $result = User::where('id', $user->id)->update(['verification_code' => $code]);
                $hook = "forgot-password-verification-code";
                $replacement['RESET_CODE'] = $code;
                $data = ['template' => $hook, 'hooksVars' => $replacement];
                Mail::to($user->email)->send(new \App\Mail\ManuMailer($data));
                $data = (object) [];
                return $this->sendResponse($data, $this->messageDefault('Your reset password code has been sent to your registered email.'));
            } else {

                return $this->sendError($this->messageDefault('This email address is not registered with us.'), '', '200');
            }
        } catch (\Exception $e) {
            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    public function resetPassword(Request $request)
    {
        $input = $request->all();
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required',
                'verification_code' => 'required',
            ], [
                'email.required' => 'Please provide email id.',
                'password.required' => 'Please provide email id.',
                'verification_code.required' => 'Please provide verification code.',

            ]);

            if ($validator->fails()) {

                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }

            $query = User::where(['email' => $request->email, 'verification_code' => $request->verification_code]);

            if ($query->exists()) {

                $user = $query->first();

                // Check if user is active

                if (!$user->status) {

                    return $this->sendError($this->messageDefault('not_activated'), '', '200');
                } elseif (!$user->email_verified_at) {

                    return $this->sendError($this->messageDefault('not_verified'), '', '200');
                }

                $password = bcrypt($request->password);

                $result = User::where('id', $user->id)->update(['password' => $password]);

                $data = (object) [];

                return $this->sendResponse($data, $this->messageDefault('Your Password Changed Successfully.'));
            } else {

                return $this->sendError($this->messageDefault('This code not valid'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    public function changePassword(Request $request)
    {

        $input = $request->all();

        $user = $request->get('Auth');

        $validator = Validator::make($request->all(), [

            'old_password' => 'required',

            'new_password' => 'required|min:6',

        ], [

            'old_password.required' => 'Please provide your old password.',

            'new_password.required' => 'Please provide your new password.',

            'new_password.min' => 'Password length should be more than 6 character.',

        ]);

        if ($validator->fails()) {

            return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
        }

        try {

            $user = User::find($user->id);

            $old_password = $user->password;

            $user->password = bcrypt($request->new_password);

            $data = (object) [];

            if (strcmp($request->old_password, $request->new_password) == 0) {

                return $this->sendError($this->messageDefault('New password cannot be same as your current password.'), '', '200');
            } elseif (!Hash::check($request->old_password, $old_password)) {

                return $this->sendError($this->messageDefault('The current password is incorrect.'), '', '200');
            } elseif ($user->save()) {

                return $this->sendResponse($data, $this->messageDefault('change_password_success'));
            } else {

                return $this->sendError($this->messageDefault('process_failed'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'));
        }
    }

    public function homeList(Request $request)
    {
        $color_codes = config('constants.CATEGORE_COLOR_CODE');
        $data = $request->all();
        $user = $request->get('Auth');
        try {
            $adsCategory = Category::where('status', 1)->where('parent_id', '0')->get(['id', 'title', 'slug', 'icon', 'color']);
            $adsCategory->each(function ($record) use ($color_codes) {
                $record->color = $color_codes[$record->color];
            });
            if (count($adsCategory) > 0) {
                $result = [];
                $adsQuery = Advertisement::with('user')->where('is_featured', 1)->where('is_publish', 1)->where('status', 1)
                    ->with([
                        'advertisement_images',
                        'location' => function ($q) {
                            $q->select('id', 'title', 'area_id');
                        },
                        'location.area' => function ($q) {
                            $q->select('id', 'title', 'city_id');
                        },
                        'city' => function ($q) {
                            $q->select('id', 'title');
                        },
                        'area' => function ($q) {
                            $q->select('id', 'title', 'city_id');
                        },
                        'location.area.city' => function ($q) {
                            $q->select('id', 'title');
                        },
                        'location.area.city' => function ($q) {
                            $q->select('id', 'title');
                        },
                        'sub_business_category' => function ($q) {
                            $q->select('id', 'title', 'parent_id');
                        },
                        'sub_business_category.parent' => function ($q) {
                            $q->select('id', 'title');
                        },
                    ]);
                $advertisements = $adsQuery->get();

                $adsArr = array();
                foreach ($advertisements as $key => $ad) {
                    $user_wishlist = UserWishlist::where(['advertisement_id' => $ad->id, 'user_id' => $user->id])->count();
                    if ($user_wishlist > 0) {
                        $is_wishlisted = 1;
                    } else {
                        $is_wishlisted = 0;
                    }
                    $advertisements[$key]['is_wishlisted'] = $is_wishlisted;
                }

                $result['adsCategory'] = $adsCategory;
                $result['featured_ads'] = $advertisements;
                return $this->sendResponse($result, $this->messageDefault('list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('list not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    public function config(Request $request)
    {
        $data = $request->all();
        try {
            $city = City::where('status', 1)->get();
            if (count($city) > 0) {
                $area = Area::where('status', 1)->get();
                $result['city'] = $city;
                $result['area'] = $area;
                return $this->sendResponse($result, $this->messageDefault('list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('list not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    public function getProfile(Request $request)
    {
        $input = $request->all();
        $user = $request->get('Auth');
        try {
            if ($user->id) {
                $userDeatils = User::where('id', $user->id)->first(['id', 'email', 'phone', 'first_name', 'last_name', 'api_token']);
                if (!empty($userDeatils)) {
                    return $this->sendResponse($userDeatils, $this->messageDefault('profile_get'));
                } else {
                    return $this->sendError($this->messageDefault('record_found'), '', '200');
                }
            } else {
                return $this->sendError($this->messageDefault('invalid_request'), '', '200');
            }
        } catch (\Exception $e) {
            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    public function updateProfile(Request $request)
    {
        $input = $request->all();
        $user = $request->get('Auth');
        try {
            $validator = Validator::make($request->all(), [
                // 'first_name' => 'required|min:3',
                //'last_name' => 'required|min:3',
                'phone' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }

            // $update['first_name'] = $input['first_name'];
            $update['last_name'] = isset($input['last_name']) ? $input['last_name'] : "";
            $update['phone'] = $input['phone'];
            //    $update['city_id'] = $input['city_id'];
            //    $update['area_id'] = $input['area_id'];
            if (!empty($request->first_name)) {
                $update['first_name'] = $input['first_name'];
            }
            // if (!empty($request->last_name)) {
            //     $update['last_name'] = $input['last_name'];
            // }
            if (!empty($request->city_id)) {
                $update['city_id'] = $input['city_id'];
            }
            if (!empty($request->area_id)) {
                $update['area_id'] = $input['area_id'];
            }
            //  =================== is_approved and store_name and description =================
            if (!empty($request->store_name) && !empty($request->description)) {
                $update['store_name'] = $input['store_name'];
                $update['description'] = $input['description'];
                $update['is_approved'] = 1;
            }
            //  =================== close  is_approved and store_name and description =================

            $update['address'] = isset($input['address']) ? $input['address'] : $user->address;
            if (isset($_FILES['profile_picture']['name']) && $_FILES['profile_picture']['name'] != '') {
                $profile_photo = time() . '.' . request()->profile_picture->getClientOriginalExtension();
                request()->profile_picture->move(storage_path('app/public/profile_pictures/'), $profile_photo);
                $input['profile_picture'] = $profile_photo;
            } else {
                $input['profile_picture'] = $user->profile_picture;
            }

            $update['profile_picture'] = (string) $input['profile_picture'];
            User::where('id', $user->id)->update($update);
            $udata = User::where('id', $user->id)->first();
            return $this->sendResponse($udata, $this->messageDefault('profile_edit'));
        } catch (\Exception $e) {
            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    public function categoryList(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        try {
            $subCategory = Subscription::where('user_id', $user->id)->where('start_date', '<=', Carbon::now())->where('end_date', '>=', Carbon::now())->pluck('category_id');

            if (count($subCategory) > 0) {
                $categoryList = Category::where('status', 1)->whereIn('id', $subCategory)->get(['id', 'title', 'slug', 'icon']);
                return $this->sendResponse($categoryList, $this->messageDefault('list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('list not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    /* submitting contact us form request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return contactResponse
     */
    public function contactUs(Request $request)
    {
        $inputs = $request->all();
        $user = $request->get('Auth');
        try {
            //validate the input data in api
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'message' => 'required',
                'subject' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }
            $insertInput = array();
            $insertInput["name"] = $inputs['name'];
            $insertInput["email"] = $inputs['email'];
            $insertInput["subject"] = $inputs['subject'];
            $insertInput["message"] = $inputs['message'];
            $enquiryData = new Contact();

            if ($enquiryData->fill($insertInput)->save()) {
                $contactData = array();
                $contactData['name'] = $enquiryData->name;
                $contactData['email'] = $enquiryData->email;
                $contactData['subject'] = $enquiryData->subject;
                $contactData['message'] = $enquiryData->message;

                return $this->sendResponse($contactData, $this->messageDefault('Your enquiry has been submitted successfully.'));
            } else {
                return $this->sendError($this->messageDefault('Your enquiry has not submitted successfully.'), '', '200');
            }
        } catch (\Exception $e) {
            return $this->sendError($this->messageDefault('oops'), '', '200');
        }
    }

    /* Event notification list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return notificationList
     */
    public function notificationList(Request $request)
    {
        $data = $request->all();
        $user = $request->get('Auth');
        try {
            $notification = EventNotification::with(['getAdsInfo.advertisement_images' => function ($q) {
                $q->where('main', 1);
            }])->where('receiver_id', $user->id)->orderByDesc('id')->get();
            if (!empty($notification)) {
                $result['notificationList'] = $notification;
                $result['first_name'] = $user->first_name;
                // $result['last_name'] = $user->last_name; 
                $result['last_name'] = isset($user->last_name) ? $user->last_name : "";
                $result['email'] = $user->email;
                $result['phone'] = $user->phone;

                return $this->sendResponse($result, $this->messageDefault('list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('list not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage());
        }
    }

    /* Internal Messaging list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return internalMessagingList
     */
    public function internalMessagingList(Request $request)
    {
        $user = $request->get('Auth');

        try {
            $discussion = Discussion::with(
                [
                    'discussionMsg',
                    'advertisement:id,title,slug,user_id',
                    'sender:id,first_name,profile_picture,last_name',
                    'receiver:id,first_name,profile_picture,last_name',
                ]
            )
                ->distinct('advertisement_id')
                ->orWhere(['receiver_id' => $user->id, 'sender_id' => $user->id])
                ->orderBy('created_at', 'DESC')->get();

            if ($discussion && count($discussion) > 0) {
                foreach ($discussion as $key => $discussionMsg) {
                    // dd($discussionMsg->sender->last_name);
                    $discussion[$key]['lastMessage'] = $discussionMsg->discussionMsg()->orderBy('created_at', 'DESC')->first();
                    //  dd($discussionMsg->receiver->last_name);
                    //   ======================== Last send check if null then send empty ============================
                    if (isset($discussionMsg->receiver->last_name) == null) {
                        $discussionMsg->receiver->last_name = "";
                    }
                    if (($discussionMsg->sender->last_name) == null) {
                        $discussionMsg->sender->last_name = "";
                    }
                    //   ======================== close Last send check if null then send empty ============================

                    if ($user->id == $discussionMsg->receiver->id) {
                        $discussion[$key]['imageSenderpath'] = !empty($discussionMsg->sender->profile_picture) ? asset('storage/profile_pictures/' . $discussionMsg->sender->profile_picture) : asset('img/no-image.png');
                    } else {
                        $discussion[$key]['imageSenderpath'] = !empty($discussionMsg->receiver->profile_picture) ? asset('storage/profile_pictures/' . $discussionMsg->receiver->profile_picture) : asset('img/no-image.png');
                    }
                }
            }

            if (!empty($discussion)) {
                return $this->sendResponse($discussion, $this->messageDefault('list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('list not found.'), '', '200');
            }

            if (!empty($discussion)) {
                return $this->sendResponse($discussion, $this->messageDefault('list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('list not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage());
        }
    }

    /* Message Conversation list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return messageConversationList
     */
    public function messageConversationList(Request $request)
    {
        $user = $request->get('Auth');
        $data = $request->all();
        try {

            //validate the input data in api
            $validator = Validator::make($request->all(), [
                'discussion_id' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }

            $discussionChat = Discussion::with([
                'discussionMsg', 'advertisement:id,title,slug,user_id', 'sender:id,first_name,profile_picture,last_name',
                'receiver:id,first_name,profile_picture,last_name',
            ])->distinct('advertisement_id')->orWhere(['receiver_id' => $user->id, 'sender_id' => $user->id])->orderBy('created_at', 'DESC')->where(['id' => $data['discussion_id']])->first();

            if (!empty($discussionChat)) {
                foreach ($discussionChat->discussionMsg as $key => $discussionVal) {
                    if (!empty($discussionVal->attachment)) {
                        $attachmentArray = json_decode($discussionVal->attachment, true);

                        if (!empty($attachmentArray)) {
                            $attArray = array();
                            $attNameArray = array();
                            foreach ($attachmentArray as $aKey => $aAttachment) {
                                $attArray[] = asset('storage/discussion/' . $aAttachment);
                                $attNameArray[] = $aAttachment;
                            }
                            $discussionChat->discussionMsg[$key]['attachment'] = $attNameArray;
                            $discussionChat->discussionMsg[$key]['attachments'] = $attArray;
                        }
                    }
                }
            }
            if (!empty($discussionChat)) {
                return $this->sendResponse($discussionChat, $this->messageDefault('list found successfully.'));
            } else {
                return $this->sendError($this->messageDefault('list not found.'), '', '200');
            }
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage());
        }
    }
    /* Message Conversation list.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return sendInternalMessagingText
     */
    // public function sendInternalMessagingText(Request $request)
    // {
    //     $user = $request->get('Auth');
    //     $data = $request->all();
    //    //  Log::info(print_r($data, true));
    //   //  die;
    //     try {

    //         //validate the input data in api
    //         $validator = Validator::make($request->all(), [
    //             'discussion_id'   => 'required',
    //             'receiver_id'     => 'required',
    //             // 'message'         => 'required',
    //         ]);
    //         if ($validator->fails()) {
    //             return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
    //         }

    //         $count = ($request->hasFile('attachment')) ? count($request->file('attachment'))  : 0;
    //         /*For message only*/
    //         if ($count == 0) {
    //             $chatHistory                    = new DiscussionMessage;
    //             $chatHistory->receiver_id       = $request->receiver_id;
    //             $chatHistory->discussion_id     = $request->discussion_id;
    //             $chatHistory->sender_id         = $user->id;
    //             $chatHistory->message           = $request->message;
    //             $chatHistory->save();
    //         }
    //         /*For message with multiple attach only*/
    //         if ($count > 0) {
    //             if ($request->hasFile('attachment')) {
    //                 $chatHistory                    = new DiscussionMessage;
    //                 $chatHistory->receiver_id       = $request->receiver_id;
    //                 $chatHistory->discussion_id     = $request->discussion_id;
    //                 $chatHistory->sender_id         = $user->id;
    //                 $chatHistory->message           = $request->message;
    //                 $attached = [];
    //                 $attArray = array();
    //                 $attNameArray = array();
    //                 foreach ($request->file('attachment') as $key => $file) {
    //                     $filename = random_int(1000, 9999) . time() . '.' . $file->guessExtension();
    //                     $path = $file->storeAs(
    //                         'public/discussion',
    //                         $filename
    //                     );
    //                     $attached[] = $filename;
    //                     $attArray[] = asset('storage/discussion/' . $filename);
    //                     $attNameArray[] = $filename;
    //                 }
    //                 $chatHistory->attachment  = json_encode($attached, true);
    //                 $chatHistory->save();
    //             }
    //         $chatHistory->attachment_new  = $attArray;
    //         }
    //         if (!empty($chatHistory)) {
    //             return $this->sendResponse($chatHistory, $this->messageDefault('Message Sent Successfully.'));
    //         } else {
    //             return $this->sendError($this->messageDefault('Message Not Sent Successfully.'), '', '200');
    //         }
    //     } catch (\Exception $e) {

    //         return $this->sendError($e->getMessage());
    //     }
    // }

    public function sendInternalMessagingText(Request $request)
    {
        $user = $request->get('Auth');
        try {
            //   validate the input data in api
            $validator = Validator::make($request->all(), [
                //   'discussion_id'   => 'required',
                'receiver_id' => 'required',
                //    'message'         => 'required|string',
            ]);
            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
            }
            $data = $request->all();
            $sender_id = $user->id;
            // $sender_id = 91;
            $receiver_id = $request->receiver_id;
            $advertisement_id = $request->advertisement_id;
            //  $chat = 0;
            // $chat = Discussion::where('sender_id', $sender_id)->where('receiver_id', $data['receiver_id'])->first();
            // if(!empty($chat)){
            //    // dd($chat->toArray());
            //     $chat->advertisement_id   = isset($data['advertisement_id']) ? $data['advertisement_id'] : $chat->advertisement_id;
            //     $chat->created_at = Carbon::now();
            // }
            // else
            // if (isset($data['advertisement_id']) && isset($data['discussion_id'])) {
            //    // dd("a");
            //     $discussion_id = $data['discussion_id'];
            //     $chat = Discussion::where('advertisement_id', $data['advertisement_id'])->where('id', $data['discussion_id'])->first();
            //     $chat->receiver_id = $receiver_id;
            //     $chat->sender_id = $sender_id;
            //     $chat->advertisement_id = $data['advertisement_id'];
            //     $chat->status = 1;
            //     $chat->created_at = Carbon::now();
            // } else

            if (isset($data['discussion_id'])) {
                $chat = Discussion::find($data['discussion_id']);

                if (!empty($chat)) {
                    // $chat->advertisement_id   = isset($data['advertisement_id']) ? $data['advertisement_id'] : "";
                    $chat->created_at = Carbon::now();
                } else {
                    return $this->sendError($this->messageDefault('Discussion id does not exist.'), '', '200');
                }
            } else {
                $validator = Validator::make($request->all(), [
                    'advertisement_id' => 'required|numeric',
                ]);
                if ($validator->fails()) {
                    return $this->sendError('Validation Error.', $validator->errors()->first(), '200');
                }
                $chat = new Discussion;
                $chat->receiver_id = $receiver_id;
                $chat->sender_id = $sender_id;
                $chat->advertisement_id = $data['advertisement_id'];
                $chat->status = 1;
            }
            if ($chat->save()) {
                $count = ($request->hasFile('attachment')) ? count($request->file('attachment')) : 0;
                /*For message only*/
                if ($count == 0) {
                    $chatHistory = new DiscussionMessage;
                    $chatHistory->receiver_id = $receiver_id;
                    $chatHistory->discussion_id = $chat->id;
                    $chatHistory->sender_id = $sender_id;
                    if (!empty($request->message)) {
                        $chatHistory->message = $request->message;
                    }
                    //  $chatHistory->message          = isset($request->message) ? $request->message : 0;
                    $chatHistory->save();
                } else {
                    /*For message with multiple attach only*/
                    if ($count > 0) {
                        if ($request->hasFile('attachment')) {
                            $chatHistory = new DiscussionMessage;
                            $chatHistory->receiver_id = $receiver_id;
                            $chatHistory->discussion_id = $chat->id;
                            $chatHistory->sender_id = $sender_id;
                            $chatHistory->message = $request->message;
                            $attached = [];
                            $attArray = array();
                            $attNameArray = array();
                            foreach ($request->file('attachment') as $key => $file) {
                                $filename = random_int(1000, 9999) . time() . '.' . $file->guessExtension();
                                $path = $file->storeAs(
                                    'public/discussion',
                                    $filename
                                );
                                $attached[] = $filename;
                                $attArray[] = asset('storage/discussion/' . $filename);
                                $attNameArray[] = $filename;
                            }
                            $chatHistory->attachment = json_encode($attached, true);
                            $chatHistory->save();
                        }
                        $chatHistory->attachment_new = $attArray;
                    }
                }
            }
            $chatid = ($chat->id) ? $chat->id : 0;

            if (!empty($chatHistory)) {
                $this->sendNotificationForReceivingMessage($receiver_id, $sender_id, $chatid);
                return $this->sendResponse($chatHistory, $this->messageDefault('Message Sent Successfully.'));
            } else {
                return $this->sendError($this->messageDefault('Message Not Sent Successfully.'), '', '200');
            }

            /*For message with multiple attach only*/
        } catch (\Exception $e) {

            return $this->sendError($e->getMessage());
        }
    }
    public function myAdslist(Request $request)
    {
        try {
            $user = $request->get('Auth');
            $adsQuery = Advertisement::with([
                'advertisement_images',
                'location.area' => function ($q) {
                    $q->select('id', 'title', 'city_id');
                },
                'location.area.city' => function ($q) {
                    $q->select('id', 'title');
                },
                'location.area.city' => function ($q) {
                    $q->select('id', 'title');
                },
                'sub_business_category' => function ($q) {
                    $q->select('id', 'title', 'parent_id');
                },
                'sub_business_category.parent' => function ($q) {
                    $q->select('id', 'title');
                },
            ]);
            if ($request->type != '') {
                $adsQuery->status($request->type);
            } else {
                $adsQuery->status(1);
            }
            $adsQuery->where("user_id", $user->id);

            $advertisements = $adsQuery->orderBy('id', 'DESC')->paginate(config('get.FRONT_PAGE_LIMIT'));
            if ($advertisements->total() > 0) {
                return $this->sendResponse($advertisements, $this->messageDefault('Record found Successfully.'));
            } else {
                return $this->sendError($this->messageDefault('Record not found.'), '', '200');
            }
        } catch (\Throwable $th) {
            return $this->sendError($e->getMessage());
        }
    }

    public function pageList(Request $request)
    {
        $input = $request->all();
        try {
            $where = array('1', '6', '18');
            //::whereIn('id',$where)->
            $settings = Page::get(['title', 'slug', 'description']);
            $singleArray = [];
            foreach ($settings as $key => $value) {
                $slug = str_replace('-', '', $value->slug);
                $singleArray[$slug] = $value->description;
            }
            if (!empty($singleArray)) {
                return $this->sendResponse($singleArray, $this->messageDefault('List found successfully.'));
            } else {
                return $this->sendResponse((object) [], $this->messageDefault('Record not found.'));
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function faqList(Request $request)
    {
        $input = $request->all();
        try {

            $faqs = Faq::where('status', 1)->get();
            if (count($faqs) > 0) {
                return $this->sendResponse($faqs, $this->messageDefault('Faq list found successfully.'));
            } else {
                return $this->sendResponse((object) [], $this->messageDefault('Faq not found.'));
            }
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        $user = $request->get('Auth');
        $input = $request->all();
        $result = User::where('id', $user->id)->update(['api_token' => '', 'device_type' => '', 'device_id' => '']);
        if ($result) {
            $data = (object) [];
            return $this->sendResponse($data, $this->messageDefault('Logout successfully.'));
        } else {
            return $this->sendError($this->messageDefault('process_failed'));
        }
    }

    public function userCheck(Request $request)
    {
        $settings = Page::get(['title', 'slug', 'description']);
        return $this->sendResponse($settings, $this->messageDefault('List found successfully.'));
    }
}
