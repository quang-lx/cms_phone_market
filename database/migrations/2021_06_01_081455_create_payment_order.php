<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('payment_method_id');
            $table->double('pay_amount', 12,2)->nullable();
            $table->string('pay_method_name', 512)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('payment_history_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_history');
    }
}
