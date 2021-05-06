<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterRatingFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('rating_file', function (Blueprint $table) {
		    $table->unsignedBigInteger('file_id');
		    $table->dropColumn('filename');
		    $table->dropColumn('file_type');
		    $table->dropColumn('file_ext');
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
