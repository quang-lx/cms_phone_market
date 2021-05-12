<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->unsignedBigInteger('ship_type_id');

            $table->string('order_type', 20);
            $table->string('status', 20);
            $table->string('payment_status', 20);
			$table->double('total_price', 12,2)->default(0);
			$table->double('discount', 12,2)->default(0);
			$table->double('ship_fee', 12,2)->default(0);
			$table->double('pay_price', 12,2)->default(0);

			$table->integer('ship_province_id')->nullable();
			$table->string('ship_province_name', 100)->nullable();
			$table->integer('ship_district_id')->nullable();
			$table->string('ship_district_name', 100)->nullable();
			$table->integer('ship_phoenix_id')->nullable();
			$table->string('ship_phoenix_name', 100)->nullable();
			$table->string('ship_address', 1024)->nullable();

			$table->softDeletes();
            $table->timestamps();
            $table->index('company_id');
            $table->index('shop_id');
            $table->index('user_id');
            $table->index('ship_type_id');
        });

	    Schema::create('order_product', function (Blueprint $table) {
		    $table->id();
		    $table->unsignedBigInteger('order_id');
		    $table->unsignedBigInteger('product_id');
		    $table->unsignedBigInteger('product_attribute_value_id')->nullable();
		    $table->integer('quatity');
		    $table->double('price', 12,2)->default(0);
		    $table->text('note');

		    $table->softDeletes();
		    $table->timestamps();
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
        Schema::dropIfExists('orders');
    }
}
