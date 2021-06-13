<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnShopIdVtTransferHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vt_transfer_history', function (Blueprint $table) {
            $table->bigInteger('shop_id')->nullable()->change();
            $table->bigInteger('to_shop_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vt_transfer_history', function (Blueprint $table) {
            //
        });
    }
}
