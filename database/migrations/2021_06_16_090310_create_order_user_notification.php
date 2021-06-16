<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderUserNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('order_user_notification', function (Blueprint $table) {
		    $table->id();
		    $table->unsignedBigInteger('user_id');
		    $table->unsignedBigInteger('order_id');
		    $table->string('title', 255);
		    $table->string('content', 512);
		    $table->string('order_type', 20)->nullable();
		    $table->string('order_status', 20)->nullable();
		    $table->index('user_id');
		    $table->index('order_id');
		    $table->index('order_type');
		    $table->index('order_status');
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
        Schema::dropIfExists('order_user_notification');
    }
}
