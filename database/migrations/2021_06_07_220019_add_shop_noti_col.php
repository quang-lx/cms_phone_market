<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShopNotiCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('order_shop_notifications', function (Blueprint $table) {

			$table->string('order_type', 20)->nullable();
			$table->string('order_status', 20)->nullable();
			$table->index('order_type');
			$table->index('order_status');
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
