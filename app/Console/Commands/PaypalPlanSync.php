<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\MembershipManager\Entities\Plan;
use Modules\MembershipManager\Http\Controllers\StripeController as StripeController;

class PaypalPlanSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crons:paypal-plan-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This cron will be run every minute and add plan into paypal system.';

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
     * @return mixed
     */
    public function handle()
    { 
        /** Stripe Product Work Start **/
        $plans = Plan::active()->with(['plan_stripe_product','plan_stripe_plan'])
                    ->whereDoesntHave('plan_stripe_product', function($q){
                        $q->where('plan_action', 1)->where('plan_status', 1);
                    })
                   ->get();
        foreach ($plans as $plan) {
            if($plan->plan_stripe_product && isset($plan->plan_stripe_product->plan_response_id)){
                $result = StripeController::updateProduct($plan,$plan->plan_stripe_product->plan_response_id);
            }else{
                $result = StripeController::createProduct($plan); 
            }
            $ret = isset($plan->plan_stripe_product->retries) ? $plan->plan_stripe_product->retries : 0;
            $morphy = $plan->plan_stripe_product ?: new \App\Models\Membership\PlanSync;
            $morphy->plan_action = 1;
            if($result['status'] == 1){
                $morphy->plan_status = 1;
                $ret = 0;
                $morphy->plan_response_id = $result['product_id'];
                $morphy->plan_response = json_encode($result['response']);
                $morphy->plan_error = NULL;
            }else{
                $morphy->plan_status = 0;
                $morphy->plan_response = json_encode($result);
                $morphy->plan_error = $result['message'] ?? '';
            }
            $morphy->retries = $ret+1;
            $plan->plan_stripe_product()->save($morphy);

            /** Stripe Plan Work Start **/
            $planResult = [];
            if($plan->plan_stripe_plan && isset($plan->plan_stripe_plan->plan_response_id)){ }else{
                $planResult = StripeController::createPlan($plan,$result['product_id']); 
            }
            if(!empty($planResult)){
                $ret = isset($plan->plan_stripe_plan->retries) ? $plan->plan_stripe_plan->retries : 0;
                $planMorphy = $plan->plan_stripe_plan ?: new \App\Models\Membership\PlanSync;
                $planMorphy->plan_action = 2;
                if($planResult['status'] == 1){
                    $planMorphy->plan_status = 1;
                    $ret = 0;
                    $planMorphy->plan_response_id = $planResult['plan_id'];
                    $planMorphy->plan_response = json_encode($planResult['response']);
                    $planMorphy->plan_error = NULL;
                }else{
                    $planMorphy->plan_status = 0;
                    $planMorphy->plan_response = json_encode($planResult);
                    $planMorphy->plan_error = $planResult['message'] ?? '';
                }
                $planMorphy->retries = $ret+1;
                $plan->plan_stripe_plan()->save($planMorphy);
            }elseif($result['status'] == 1){
                $planMorphy = $plan->plan_stripe_plan ?: new \App\Models\Membership\PlanSync;
                $planMorphy->plan_status = 1;
                $planMorphy->retries = 1;
                $plan->plan_stripe_plan()->save($planMorphy);
            }
            /** Stripe Plan Work End **/
        }
        /** Stripe Product Work End **/

    }
}
