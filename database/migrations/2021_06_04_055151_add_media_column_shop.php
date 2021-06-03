<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMediaColumnShop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('medias', function (Blueprint $table) {
			$table->unsignedBigInteger('company_id')->nullable();
			$table->unsignedBigInteger('shop_id')->nullable();
			$table->index('company_id');
			$table->index('shop_id');
		});
		Schema::table('mediables', function (Blueprint $table) {

			$table->index('zone');
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
