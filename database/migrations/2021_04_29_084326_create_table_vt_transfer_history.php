<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableVtTransferHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vt_transfer_history', function (Blueprint $table) {
            $table->id();
            $table->string('title', 1024);
            $table->timestamp('received_at');
	        $table->unsignedBigInteger('shop_id');
	        $table->unsignedBigInteger('company_id');
	        $table->bigInteger('created_by')->nullable();
	        $table->bigInteger('updated_by')->nullable();
	        $table->softDeletes();
	        $table->timestamps();
	        $table->index('shop_id');
	        $table->index('company_id');
        });
	    Schema::create('vt_transfer_history_detail', function (Blueprint $table) {
		    $table->id();
		    $table->unsignedBigInteger('vt_transfer_id');
		    $table->unsignedBigInteger('vt_product_id');
		    $table->integer('amount');
		    $table->softDeletes();
		    $table->timestamps();

	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vt_transfer_history');
        Schema::dropIfExists('vt_transfer_history_detail');
    }
}
