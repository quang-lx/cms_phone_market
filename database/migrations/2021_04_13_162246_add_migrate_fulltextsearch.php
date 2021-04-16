<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMigrateFulltextsearch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE shop ADD FULLTEXT INDEX `ft_name_address` (name, address);');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE shop ADD FULLTEXT INDEX `ft_name` (name);');
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE product ADD FULLTEXT INDEX `ft_p_name` (name);');
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
