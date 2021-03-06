<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableVtCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('vt_category', function (Blueprint $table) {
		    $table->id();
		    $table->string('name', 512);
		    $table->unsignedBigInteger('shop_id')->nullable();
		    $table->unsignedBigInteger('company_id')->nullable();
		    $table->bigInteger('parent_id')->nullable()->index();
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
    }
}
