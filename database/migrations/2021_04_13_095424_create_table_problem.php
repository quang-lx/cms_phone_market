<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProblem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problems', function (Blueprint $table) {
            $table->id();
            $table->string('title', 512);
	        $table->softDeletes();
            $table->timestamps();
        });
	    Schema::create('product_problem', function (Blueprint $table) {
		    $table->id();
		    $table->unsignedBigInteger('product_id')->index();
		    $table->unsignedBigInteger('problem_id')->index();
		    $table->softDeletes();
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
        Schema::dropIfExists('problems');
    }
}
