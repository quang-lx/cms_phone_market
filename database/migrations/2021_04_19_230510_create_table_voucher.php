<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableVoucher extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('shop_id')->nullable();
            $table->smallInteger('type');
            $table->string('title',512);
            $table->string('code', 20);
            $table->smallInteger('discount_type');
            $table->double('discount_amount',12,2);
            $table->double('require_min_amount',12,2)->default(0);
            $table->timestamp('actived_at')->nullable();
            $table->timestamp('expired_at')->nullable();

            $table->integer('total')->default(0);
            $table->integer('total_used')->default(0);


            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index('company_id');
            $table->index('shop_id');
            $table->index('code');
        });

        Schema::create('voucher_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('voucher_id');

            $table->index(['product_id', 'voucher_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
