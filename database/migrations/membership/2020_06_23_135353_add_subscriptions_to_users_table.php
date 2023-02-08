<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubscriptionsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'stripe_customer_id')) {
                    $table->string('stripe_customer_id',255)->nullable();
                } 
                if (!Schema::hasColumn('users', 'is_subscribed')) {
                    $table->integer('is_subscribed')->length(5)->default(0);
                } 
                if (!Schema::hasColumn('users', 'plan_id')) {
                    $table->string('plan_id',10)->nullable();
                } 
                if (!Schema::hasColumn('users', 'subscription_id')) {
                    $table->string('subscription_id',255)->nullable();
                } 
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'stripe_customer_id')) {
                $table->dropColumn('stripe_customer_id');
            }
            if (!Schema::hasColumn('users', 'is_subscribed')) {
                $table->dropColumn('is_subscribed');
            }
            if (!Schema::hasColumn('users', 'plan_id')) {
                $table->dropColumn('plan_id');
            }
            if (!Schema::hasColumn('users', 'subscription_id')) {
                $table->dropColumn('subscription_id');
            }
        });
    }
}
