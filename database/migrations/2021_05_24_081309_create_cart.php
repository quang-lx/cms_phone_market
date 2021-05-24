<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('carts', function (Blueprint $table) {
		    $table->id();
		    $table->unsignedBigInteger('user_id')->nullable();

		    $table->softDeletes();
		    $table->timestamps();

	    });

	    Schema::create('cart_product', function (Blueprint $table) {
		    $table->id();
		    $table->unsignedBigInteger('cart_id');
		    $table->unsignedBigInteger('company_id');
		    $table->unsignedBigInteger('shop_id');
		    $table->unsignedBigInteger('product_id');
		    $table->unsignedBigInteger('product_attribute_value_id')->nullable();
		    $table->integer('quatity');
		    $table->text('note')->nullable();

		    $table->softDeletes();
		    $table->timestamps();
		    $table->index('company_id');
		    $table->index('shop_id');
		    $table->index('order_id');
		    $table->index('product_id');
		    $table->index('product_attribute_value_id');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart');
    }
}
