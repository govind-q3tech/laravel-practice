<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->length(5)->nullable();
            $table->integer('advertisement_id')->length(5)->nullable();
            $table->integer('package_id')->length(5)->nullable();
            $table->string('transactionid',191)->nullable();
            $table->float('textprice', 8, 2)->default(0)->nullable();
            $table->float('videoprice', 8, 2)->default(0)->nullable();
            $table->float('imageprice', 8, 2)->default(0)->nullable();
            $table->float('textsize', 8, 2)->default(0)->nullable();
            $table->float('video_size', 8, 2)->default(0)->nullable();
            $table->float('image_size', 8, 2)->default(0)->nullable();
            $table->float('topprice', 8, 2)->default(0)->nullable();
            $table->float('amount', 8, 2)->default(0)->nullable();
            $table->string('trackingid',191)->nullable();
            $table->string('payment_status',191)->nullable();
            $table->string('payment_method',191)->nullable();
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
        Schema::dropIfExists('payments');
    }
}
