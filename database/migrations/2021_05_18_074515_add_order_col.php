<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('orders', function (Blueprint $table) {
		   $table->boolean('type_other')->default(false)->nullable();
	    });
	    Schema::table('order_product', function (Blueprint $table) {

//		    $table->double('price', 12, 2)->default(0)->nullable();
//		    $table->double('price_sale', 12, 2)->default(0)->nullable();
		    $table->text('note')->nullable()->change();
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
