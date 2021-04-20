<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProductOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50);
            $table->string('name', 512);
	        $table->unsignedBigInteger('company_id')->nullable();
	        $table->unsignedBigInteger('shop_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
	        $table->index('company_id');
	        $table->index('shop_id');
	        $table->index('name');
        });
	    Schema::create('attribute_values', function (Blueprint $table) {
		    $table->id();
		    $table->text('name');
		    $table->unsignedBigInteger('attribute_id');
		    $table->unsignedBigInteger('company_id')->nullable();
		    $table->unsignedBigInteger('shop_id')->nullable();
		    $table->softDeletes();
		    $table->timestamps();
		    $table->index('company_id');
		    $table->index('shop_id');
	    });
	    Schema::create('product_attributes', function (Blueprint $table) {
		    $table->id();
		    $table->unsignedBigInteger('attribute_id');
		    $table->unsignedBigInteger('product_id');
		    $table->unsignedBigInteger('company_id')->nullable();
		    $table->unsignedBigInteger('shop_id')->nullable();
		    $table->softDeletes();
		    $table->timestamps();
		    $table->index('company_id');
		    $table->index('shop_id');
	    });
	    Schema::create('product_attribute_values', function (Blueprint $table) {
		    $table->id();
		    $table->unsignedBigInteger('value_id');
		    $table->unsignedBigInteger('attribute_id');
		    $table->unsignedBigInteger('product_id');
		    $table->double('price', 12,2 )->nullable();
		    $table->double('sale_price', 12,2 )->nullable();
		    $table->integer('amount')->nullable();
		    $table->integer('remain_amount')->nullable();
		    $table->unsignedBigInteger('company_id')->nullable();
		    $table->unsignedBigInteger('shop_id')->nullable();
		    $table->softDeletes();
		    $table->timestamps();
		    $table->index('company_id');
		    $table->index('shop_id');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attributes');
    }
}
