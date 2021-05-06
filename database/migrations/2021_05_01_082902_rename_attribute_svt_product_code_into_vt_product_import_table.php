<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameAttributeSvtProductCodeIntoVtProductImportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vt_import_product', function (Blueprint $table) {
            $table->unsignedBigInteger('vt_product_id')->nullable();
            $table->dropColumn('vt_product_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vt_import_product', function (Blueprint $table) {
        });
    }
}
