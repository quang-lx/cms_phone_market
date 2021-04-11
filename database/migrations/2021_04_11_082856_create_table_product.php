<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('name', 1024);
            $table->string('sku', 20);
            $table->smallInteger('status');
            $table->smallInteger('p_state');
            $table->text('description')->nullable();
            $table->double('p_weight', 12,2)->nullable();
            $table->double('s_long', 12,2)->nullable();
            $table->double('s_width', 12,2)->nullable();
            $table->double('s_height', 12,2)->nullable();
            $table->bigInteger('brand_id')->nullable()->index();
            $table->bigInteger('company_id')->nullable()->index();
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
        Schema::dropIfExists('product');
    }
}
