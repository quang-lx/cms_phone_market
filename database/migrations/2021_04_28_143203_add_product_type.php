<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('product', function (Blueprint $table) {

		    $table->smallInteger('type')->default(1);
		    $table->integer('fix_time')->default(0)->nullable();
		    $table->integer('warranty_time')->default(0)->nullable();
		    $table->index('type');
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
