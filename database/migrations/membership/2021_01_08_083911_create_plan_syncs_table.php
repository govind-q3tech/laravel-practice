<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlanSyncsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_syncs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('plan_action')->nullable()->comment('1:stripe product, 2:stripe plan, 3:paypal product, 4:paypal plan');
            $table->smallInteger('plan_status')->nullable()->comment('1: updated on plan, 0: need to re update on plan');
            $table->string('plan_response_id',200)->nullable()->comment('This is uniqeue id those returned fron plan');
            $table->longText('plan_response')->nullable();
            $table->mediumText('plan_error')->nullable();
            $table->integer('retries')->nullable();
            $table->nullableMorphs('plansyncable');
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
        Schema::dropIfExists('plan_syncs');
    }
}
