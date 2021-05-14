<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShopCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('shop_category', function (Blueprint $table) {
		    $table->id();
		    $table->unsignedBigInteger('shop_id');
		    $table->unsignedBigInteger('category');
		    $table->string('type', 20);

		    $table->timestamps();
		    $table->index('shop_id');
		    $table->index('category');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
