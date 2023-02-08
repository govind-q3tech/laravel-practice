<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            // $table->bigIncrements('id');
            $table->id();
            $table->integer('user_id')->length(5)->nullable();
            $table->string('amount',100)->nullable();
            $table->string('transaction_id',255)->nullable();
            $table->longText('transaction_response')->nullable();
            $table->string('type',100)->default("success")->comment("success, failed");
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
        Schema::dropIfExists('transactions');
    }
}
