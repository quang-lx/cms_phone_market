<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnShopNoti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('order_shop_notifications', function (Blueprint $table) {

		    $table->unsignedBigInteger('order_id')->nullable()->change();
		    $table->unsignedBigInteger('vt_transfer_id')->nullable();
			$table->smallInteger('noti_type')->default(1)->nullable();
		    $table->index('shop_id');
		    $table->index('order_id');
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
