<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderVoucher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('shop_voucher_id')->nullable();
            $table->string('shop_voucher_code',20)->nullable();
            $table->double('shop_discount',12,2)->nullable();

            $table->unsignedBigInteger('sys_voucher_id')->nullable();
            $table->string('sys_voucher_code',20)->nullable();
            $table->double('sys_discount',12,2)->nullable();

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
