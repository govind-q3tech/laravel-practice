<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('last_name')->after('name')->nullable();
            $table->string('username')->after('email');
            $table->string('phone')->after('username');
            $table->string('billing_address_1')->after('phone')->nullable();
            $table->string('billing_address_2')->after('billing_address_1')->nullable();
            $table->string('billing_town')->after('billing_address_2')->nullable();
            $table->string('billing_postcode')->after('billing_town')->nullable();
            $table->string('billing_country')->after('billing_postcode')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_name');
            $table->dropColumn('username');
            $table->dropColumn('billing_address_1');
            $table->dropColumn('billing_address_2');
            $table->dropColumn('billing_town');
            $table->dropColumn('billing_postcode');
            $table->dropColumn('billing_country');
        });
    }
}
