<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            // $table->bigIncrements('id');
            $table->id();
            $table->string('live_plan_id',255)->nullable();
            $table->string('title',255);
            //$table->integer('type')->length(5)->default(1)->comment("1 build, 2 land");
            $table->float('amount',16,2)->default(0.00);
            $table->integer('duration')->length(5)->default(1)->comment("1) Month, 2) year");
            //$table->integer('project_allowed')->length(5)->default(1);
            $table->text('description');
            $table->string('stripe_plan_id',255)->nullable();
            $table->text('stripe_plan_response')->nullable();
            $table->tinyInteger('status')->length(5)->default(1);
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
        Schema::dropIfExists('plans');
    }
}
