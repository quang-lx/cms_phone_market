<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportVt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vt_import_excel', function (Blueprint $table) {
            $table->id();
            $table->string('filepath');
            $table->integer('number_product')->default(0)->nullable();
            $table->smallInteger('status')->default(1)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
	    Schema::create('vt_import_product', function (Blueprint $table) {
		    $table->id();
		    $table->unsignedBigInteger('vt_import_excel_id');
		    $table->string('vt_product_code',30);
		    $table->string ('vt_product_name', 512);
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
        Schema::dropIfExists('vt_import_excel');
    }
}
