<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableVtShopProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vt_shop_product', function (Blueprint $table) {
            $table->id();
	        $table->unsignedBigInteger('vt_product_id');
	        $table->unsignedBigInteger('shop_id')->nullable();
	        $table->unsignedBigInteger('company_id')->nullable();
	        $table->integer('amount');
	        $table->bigInteger('created_by')->nullable();
	        $table->bigInteger('updated_by')->nullable();
	        $table->softDeletes();
	        $table->timestamps();
	        $table->index('shop_id');
	        $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vt_shop_product');
    }
}
