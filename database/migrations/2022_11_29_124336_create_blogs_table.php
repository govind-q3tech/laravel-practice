<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('blog_categories_id');
            $table->string('title','200');
            $table->string('slug','200');
            $table->longText('description');
            $table->string('images','200');
            $table->tinyInteger('status')->default('1');
            $table->string('meta_title','200');
            $table->string('meta_keyword','200');
            $table->string('sub_title','200');
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
        Schema::dropIfExists('blogs');
    }
}
