<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationUserCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->bigInteger('company_id')->nullable()->index();
        });
        Schema::table('roles', function (Blueprint $table) {

            $table->bigInteger('company_id')->nullable()->index();
        });

        Schema::table('permissions', function (Blueprint $table) {

            $table->bigInteger('company_id')->nullable()->index();
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
