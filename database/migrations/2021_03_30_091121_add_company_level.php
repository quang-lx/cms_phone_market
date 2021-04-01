<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyLevel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company', function (Blueprint $table) {
            $table->string('phone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->tinyInteger('level')->default(1)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->text('address')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {

            $table->integer('is_admin_company')->default(0)->nullable()->index();
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
