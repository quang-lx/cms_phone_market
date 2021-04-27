<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p_information', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
	        $table->unsignedBigInteger('shop_id')->nullable();
	        $table->unsignedBigInteger('company_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
	        $table->index('shop_id');
	        $table->index('company_id');

        });
	    Schema::create('production_information', function (Blueprint $table) {
		    $table->id();
		    $table->unsignedBigInteger('information_id');
		    $table->unsignedBigInteger('product_id');
		    $table->string('value', 255);

		    $table->timestamps();
		    $table->softDeletes();
		    $table->index('information_id');
		    $table->index('product_id');
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('p_information');
    }
}
