<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableShopRating extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('rating_shop', function (Blueprint $table) {
		    $table->id();
		    $table->unsignedBigInteger('shop_id')->nullable();
		    $table->unsignedBigInteger('user_id');
		    $table->integer('rating')->nullable();
		    $table->string('comment', 512)->nullable();
		    $table->unsignedBigInteger('parent_id')->nullable();
		    $table->timestamps();
	    });
	    Schema::create('rating_shop_file', function (Blueprint $table) {
		    $table->id();
		    $table->unsignedBigInteger('shop_rating_id');
		    $table->unsignedBigInteger('file_id');
		    $table->timestamps();
	    });

	    Schema::table('shop', function (Blueprint $table) {
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
        Schema::dropIfExists('rating_shop');
    }
}
