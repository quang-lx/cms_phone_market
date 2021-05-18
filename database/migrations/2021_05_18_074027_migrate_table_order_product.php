<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateTableOrderProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('order_product', function (Blueprint $table) {
		    $table->dropColumn('sale');
		    $table->dropColumn('price_sale');
		    $table->double('sale', 12, 2)->default(0)->nullable();
		    $table->double('price_sale', 12, 2)->default(0)->nullable();
		    $table->text('note')->nullable();
		    $table->unsignedBigInteger('product_id')->nullable()->change();
		    $table->string('product_title', 1024)->nullable();
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
