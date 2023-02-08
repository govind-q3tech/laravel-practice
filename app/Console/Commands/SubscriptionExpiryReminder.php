<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EventNotification;
use App\Models\Membership\Subscription;
use App\Models\User;
use Mail;
use Carbon\Carbon;
use App\Models\Advertisement;

class SubscriptionExpiryReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crons:subscription-expiry-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This cron will be run before 2 days of plan expiry';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {

            $plans = Subscription::with(['plans'])->where('status',1)->get()->toArray();

            if(!empty($plans)) {
                foreach ($plans as $key => $plan) {
                    $user                   = User::find($plan['user_id']);
                    $now                    = Carbon::now()->timestamp; 
                    $end_date               = $plan['end_date'];
                    $finalDate              = strtotime($end_date);
                    $notification_status    = config('constants.NOTIFICATION_LIST');

                    if($now <= $finalDate){
                        $currentTime                = time();
                        $verificationDate           = strtotime($plan['end_date']);
                        $datediff                   = $verificationDate - $currentTime;
                        $diff                       = rtrim(round($datediff / (60 * 60 * 24)), 0);

                        if($diff == 2) {
                            
                            $event_notification_user = [
                                'sender_id'     => 1,
                                'sender_type'   => 'A',
                                'receiver_id'   => $plan['user_id'],
                                'receiver_type' => 'U',
                                'advertisement_id' => 0,
                                'events'        => 'M',
                                'message'       => $notification_status['M']['message'],
                                'is_read'       => 0,
                            ];

                            EventNotification::create($event_notification_user);

                            //For sending mail to user//
                            $hook = "subscription_plan_expire_reminder"; 
                            $replacement['USER_NAME']   = $user->first_name;
                            $replacement['PLAN']        = $plan['plans']['title'];
                            $replacement['END_DATE']    = date("d-m-Y", strtotime($plan['end_date']));
                                
                            $data = ['template' => $hook, 'hooksVars' => $replacement];
                            Mail::to($user->email)->send(new \App\Mail\ManuMailer($data));
                            // For sending mail to user//


                        }
                    }
                    else{
                            Advertisement::whereIn('status',[1,2])->where('business_category_id',$plan['category_id'])->where('user_id',$plan['user_id'])->update(['status' => 0,'admin_approve' => 0,'is_publish' => 0,'sponsored' => 0  ]);

                            Subscription::where('id',$plan['id'])->update(['status' => 0 ]);

                            $event_notification_user = [
                                'sender_id'     => 1,
                                'sender_type'   => 'A',
                                'receiver_id'   => $plan['user_id'],
                                'receiver_type' => 'U',
                                'advertisement_id' => 0,
                                'events'        => 'N',
                                'message'       => $notification_status['N']['message'],
                                'is_read'       => 0,
                            ];

                            EventNotification::create($event_notification_user);

                            //For sending mail to user//
                            $hook = "subscription_plan_expired"; 
                            $replacement['USER_NAME']   = $user->first_name;
                            $replacement['PLAN']        = $plan['plans']['title'];
                            $replacement['END_DATE']    = date("d-m-Y", strtotime($plan['end_date']));
                                
                            $data = ['template' => $hook, 'hooksVars' => $replacement];
                            Mail::to($user->email)->send(new \App\Mail\ManuMailer($data));
                            // For sending mail to user//
                            
                    }
                    
                }
            }
            echo "Notification reminder send successfully";

        } catch(\Exception $e) {
            echo $e->getMessage();
        }
    }
}
