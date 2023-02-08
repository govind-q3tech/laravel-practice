<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Kyslik\ColumnSortable\Sortable;
use Mail, DB;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use Sortable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        "first_name",
        "last_name",
        "email",
        "phone",
        "city_id",
        "area_id",
        "location_id",
        "address",
        "store_name",
        "description",
        "profile_picture",
        "email_verified_at",
        "password",
        'two_factor_secret',
        'two_factor_recovery_codes',
        "remember_token",
        "plan_id",
        "subscription_id",
        "device_id",
        "device_type",
        "api_token",
        "verification_code",
        "is_approved",
        "stripe_customer_id",
        "status",
        "created_at",
        "updated_at",
        "facebook_url",
        "twitter_url",
        "instagram_url",
        "youtube_url",
        "wp_id",
        "email_change"
    ];


    /**
     * The sortable used for column sort.
     *
     * @var array
     */
    public $sortable = ["first_name", "last_name", "email", 'status', 'email_verified_at', 'created_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url', 'profile_image'
    ];

    // protected $appends = [
    //     'profile_photo_url', 'profile_image', 'last_name'
    // ];
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::saved(function ($model) {
            $model->sendRegEmail($model);
        });
    }

    public function getProfileImageAttribute()
    {
        return !empty($this->profile_picture) ? asset('storage/profile_pictures/' . $this->profile_picture) : "";
    }
    //    last_name use for where will will send blank
    // public function getLastNameAttribute()
    // {
    //     return !empty($this->last_name) ?  $this->last_name : "";
    // }


    public function user_reviews()
    {
        return $this->hasMany(\App\Models\UserReview::class, 'user_id');
    }

    public function user_wishlists()
    {
        return $this->hasMany(\App\Models\UserWishlist::class, 'user_id');
    }

    public function subscription()
    {
        $date = date('Y-m-d H:i:s');
        return $this->hasOne(\App\Models\Membership\Subscription::class, 'user_id', 'id')->where('start_date', '<=', $date)->where('end_date', '>=', $date);
    }


    public function plan()
    {
        return $this->belongsTo(\App\Models\Membership\Plan::class, 'plan_id');
    }

    public function sponsor()
    {
        return $this->plan()->where(['plan_icon' => 'featured', 'plan_icon' => 'sponsered']);
    }


    public function scopeFilter($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where(function ($query) use ($keyword) {
                $query->where('first_name', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('last_name', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('email', 'LIKE', '%' . $keyword . '%');
            });
        }
        return $query;
    }

    public function scopeTypefilter($query, $keyword)
    {
        if (!empty($keyword)) {
            if ($keyword == 'advertisers') {
                $query->where('is_approved', '>', 0);
            } elseif ($keyword == 'customers') {
                $query->where('is_approved', 0);
            }
        }
        return $query;
    }

    /**
     * Get the only lender detail from existing crm.
     * Nee to delete after remove from all places and use xero_contact instead of this method
     */
    public function data_user()
    {
        return $this->morphOne(\App\Models\DataSync::class, 'datasyncable')->where('table_type', 1);
    }

    public function sendRegEmail($model)
    {
        if (\Request::route()->getName() == "admin.users.store") {
            // dd($model);
            // dd(request('random'));
            if ($model->getAttribute('email') && $model->getOriginal('email') != $model->getAttribute('email')) {

                $token = app('auth.password.broker')->createToken($model);
                $replacement['token'] = $token;
                $userData = '';
                $userData .= "Email: " . $model->getAttribute('email') . "<br>";
                $userData .= "Password: " . request('random');
                // $userData .= '<p> <strong> Please note it may take up to 24 hours for your account to be activated. You will be sent a confirmation email once you’re able to log in. Thanks for your patience, we really appreciate it, Please click on below link to email verification </strong> <p>';
                // $replacement['VERIFY_LINK'] = url('/users/verify-account/' . $model->getAttribute('id') . '/' . $token);
                // $replacement['USER_NAME'] = $model->getAttribute('first_name') . ' ' . $model->getAttribute('last_name');
                DB::table('user_verifications')->insert([
                    'user_id' => $model->getAttribute('id'),
                    'verification_code' => $token, //change 60 to any length you want
                    'type' => 1,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ]);
                $replacement['USER_INFO'] = $userData;
                $replacement['USER_NAME'] = $model->getAttribute('first_name') . ' ' . $model->getAttribute('last_name');
                $replacement['USER_EMAIL'] = $model->getAttribute('email');
                $hook = "welcome_email_for_user_by_admin";
                $data = ['template' => $hook, 'hooksVars' => $replacement];

                Mail::to($model->getAttribute('email'))->send(new \App\Mail\ManuMailer($data));
            }
        } else {
            if ($model->getAttribute('email') && $model->getOriginal('email') != $model->getAttribute('email')) {

                $token = app('auth.password.broker')->createToken($model);
                $verificationUrl = $this->verificationUrl($this);
                $replacement['token'] = $token;
                $userData = '';
                $userData .= "User Name: " . $model->getAttribute('email') . "<br>";
                $userData .= '<p> <strong> Please note it may take up to 24 hours for your account to be activated. You will be sent a confirmation email once you’re able to log in. Thanks for your patience, we really appreciate it, Please click on below link to email verification </strong> <p>';

                $hook = "verification-link";
                $replacement['VERIFY_LINK'] = $verificationUrl;
                $replacement['USER_NAME'] = $model->getAttribute('first_name') . ' ' . $model->getAttribute('last_name');

                $replacement['USER_INFO'] = $userData;
                $replacement['USER_NAME'] = $model->getAttribute('first_name') . ' ' . $model->getAttribute('last_name');
                $replacement['USER_EMAIL'] = $model->getAttribute('email');
                $data = ['template' => $hook, 'hooksVars' => $replacement];
                Mail::to($model->getAttribute('email'))->send(new \App\Mail\ManuMailer($data));
            } elseif (\Request::route()->getName() == "frontend.forgot-password.email") {

                $token = app('auth.password.broker')->createToken($model);
                $userData = '';
                $replacement['token'] = $token;
                $hook = "create-new-password";
                $url = url("/reset-password/{$token}") . '?email=' . $model->email;
                $replacement['CREATE_NEW_PASSWORD'] = $url;
                $replacement['USER_INFO'] = $userData;
                $replacement['USER_NAME'] = $model->getAttribute('first_name') . ' ' . $model->getAttribute('last_name');
                $replacement['USER_EMAIL'] = $model->getAttribute('email');
                $data = ['template' => $hook, 'hooksVars' => $replacement];
                Mail::to($model->getAttribute('email'))->send(new \App\Mail\ManuMailer($data));
            }
        }
    }

    public function sendApprovedEnail($model)
    {
        $hook = "after-approve-advertiser-welcome-email";
        $replacement['USER_INFO'] = '';
        $replacement['USER_NAME'] = $model->getAttribute('first_name');
        $replacement['USER_EMAIL'] = $model->getAttribute('email');
        $data = ['template' => $hook, 'hooksVars' => $replacement];
        Mail::to($model->getAttribute('email'))->send(new \App\Mail\ManuMailer($data));
    }

    /**
     * Send a password reset notification to the user.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        // $url = 'https://example.com/reset-password?token='.$token;
        // $this->notify(new ResetPasswordNotification($url));

        $verificationUrl = $this->verificationUrl($this);

        if (\Request::route()->getName() == "admin.password.email") {
            $hook = "forgot-password-email";
            //$replacement['RESET_PASSWORD_URL'] = $token;
            $replacement['RESET_PASSWORD_URL'] = url("/reset-password/{$token}/?email=" . (base64_encode(request('email'))));
        } else if (\Request::route()->getName() == "verification.resend") {
            $hook = "resend_verification_notification";
            $replacement['VERIFICATION_LINK'] = $verificationUrl;
        } else if ($this->getAttribute('password') == null) {
            $hook = "create-new-password";
            $token = app('auth.password.broker')->createToken($this);
            $url = url("/password/create/{$token}/?email=" . (base64_encode($this->getEmailForVerification())));
            $replacement['CREATE_NEW_PASSWORD'] = $url;
        }
        $replacement['USER_INFO'] = '';
        $replacement['USER_NAME'] = $this->first_name;
        $replacement['USER_EMAIL'] = $this->getEmailForVerification();
        $data = ['template' => $hook, 'hooksVars' => $replacement];

        Mail::to($this->getEmailForVerification())->send(new \App\Mail\ManuMailer($data));
    }

    /**
     * Send the email verification notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendEmailVerificationNotification()
    {

        $verificationUrl = $this->verificationUrl($this);
        $hook = "";
        if (\Request::route()->getName() == "frontend.verification.send" || \Request::route()->getName() == "admin.verification.send") {
            $hook = "resend_verification_notification";
            $replacement['VERIFICATION_LINK'] = $verificationUrl;
        } else if (\Request::route()->getName() == "admin.admin-users.store") {
            // $hook = "welcome-email";
            $hook = "verification-link";
            $replacement['VERIFY_LINK'] = $verificationUrl;
        } else if ($this->getAttribute('password') == null) {
            $hook = "create-new-password";
            $token = Password::broker('admin_users')->createToken($this);
            $url = url("/password/create/{$token}/?email=" . (base64_encode($this->getEmailForVerification())));
            $replacement['CREATE_NEW_PASSWORD'] = $url;
        }
        $replacement['USER_INFO'] = '';
        $replacement['USER_NAME'] = $this->first_name;
        $replacement['USER_EMAIL'] = $this->getEmailForVerification();
        if (!empty($hook)) {
            $data = ['template' => $hook, 'hooksVars' => $replacement];
            // dump($data);
            // dd($this->getEmailForVerification());
            //Mail::to($this->getEmailForVerification())->send(new \App\Mail\ManuMailer($data));
            return false;
        }
    }


    /**
     * Send the email verification notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendWelcomeMail()
    {

        $hook = "welcome-email";
        $replacement['USER_INFO'] = '';
        $replacement['USER_NAME'] = $this->first_name;
        $replacement['USER_EMAIL'] = $this->getEmailForVerification();
        $data = ['template' => $hook, 'hooksVars' => $replacement];
        // dd($data);
        // dd($this->getEmailForVerification());
        Mail::to($this->getEmailForVerification())->send(new \App\Mail\ManuMailer($data));
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'frontend.verification.verify',
            \Illuminate\Support\Carbon::now()->addMinutes(\Illuminate\Support\Facades\Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Send the email verification notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendReverificationMail($requestData)
    {
        $verificationUrl = $this->verificationUrl($this);
        $hook = "resend_verification_on_change_email";
        $replacement['USER_INFO'] = '';
        $replacement['USER_NAME'] = $requestData['first_name'];
        $replacement['USER_EMAIL'] = $requestData['email'];
        $replacement['USER_OLD_EMAIL'] = $this->email;
        $replacement['VERIFY_LINK'] = $verificationUrl;
        $data = ['template' => $hook, 'hooksVars' => $replacement];

        Mail::to($requestData['email'])->send(new \App\Mail\ManuMailer($data));
    }
}
