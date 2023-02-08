<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedTinyInteger('stripe_plan_id')->length(11)->nullable();
            $table->string('title',255)->nullable();
            $table->string('duration',255)->nullable();
            $table->string('plan_interval',255)->nullable();
            $table->string('trail_days',255)->nullable();
            $table->float('amount', 8, 2)->default(0);
            $table->text('description');
            $table->boolean('status')->default(1)->comment("1=active, 0=in active");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_plans');
    }
}
