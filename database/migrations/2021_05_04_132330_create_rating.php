<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRating extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rating', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->integer('rating')->nullable();
            $table->string('comment', 512)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->timestamps();
        });
	    Schema::create('rating_file', function (Blueprint $table) {
		    $table->id();
		    $table->unsignedBigInteger('rating_id');
		    $table->string('filename', 255);
		    $table->string('file_type', 20)->nullable();
		    $table->string('file_ext', 20)->nullable();
		    $table->timestamps();
	    });

	    Schema::table('product', function (Blueprint $table) {


		    $table->integer('rating_avg')->default(0)->nullable();
		    $table->integer('rating_user')->default(0)->nullable();
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rating');
    }
}
