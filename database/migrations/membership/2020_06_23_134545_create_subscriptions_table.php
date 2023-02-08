<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            // $table->bigIncrements('id');
            $table->id();
            $table->integer('plan_id')->length(5)->nullable();
            $table->integer('user_id')->length(5)->nullable();
            $table->string('subscription_id',255)->nullable();
            $table->longText('subscription_response')->nullable();
            $table->string('start_date',255)->nullable();
            $table->string('end_date',255)->nullable();
            $table->tinyInteger('status')->length(5)->default(1);
            $table->timestamp('cancelled_at', 0);
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
        Schema::dropIfExists('subscriptions');
    }
}
