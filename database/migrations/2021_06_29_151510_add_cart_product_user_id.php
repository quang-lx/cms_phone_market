<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCartProductUserId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('order_product', function (Blueprint $table) {
		    $table->unsignedBigInteger('user_id')->nullable();
		    $table->index('user_id');

		    $table->timestamp('warranty_time')->nullable();
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
