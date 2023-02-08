<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->integer('type')->nullable()->after('slug')->comment("1->country, 2->county, 3 -> city , 4 -> town");
            $table->integer('country_id')->nullable()->after('type')->comment("1->country, 2->county, 3 -> city , 4 -> town");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('country_id');
        });
    }
}
