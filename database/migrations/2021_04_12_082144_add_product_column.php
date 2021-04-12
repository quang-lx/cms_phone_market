<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('product', function (Blueprint $table) {
	    	$table->integer('amount');
	    	$table->double('price', 12,2);
		    $table->bigInteger('created_by')->nullable();
		    $table->bigInteger('updated_by')->nullable();
		    $table->bigInteger('deleted_by')->nullable();
		    $table->softDeletes();
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
