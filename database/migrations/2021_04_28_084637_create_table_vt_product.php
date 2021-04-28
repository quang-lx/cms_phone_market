<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableVtProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vt_product', function (Blueprint $table) {
	        $table->id();
	        $table->string('name', 512);
	        $table->string('code', 30);
	        $table->double('price', 12,2);
	        $table->unsignedInteger('amount')->default(0)->nullable();
	        $table->unsignedBigInteger('vt_category_id')->nullable();
	        $table->unsignedBigInteger('shop_id')->nullable();
	        $table->unsignedBigInteger('company_id')->nullable();
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
        Schema::dropIfExists('vt_product');
    }
}
