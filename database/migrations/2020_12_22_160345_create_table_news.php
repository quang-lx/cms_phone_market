<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableNews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title',512);
            $table->string('slug',512);
            $table->unsignedInteger('category_id')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('author',512)->nullable();
            $table->string('type',512)->nullable();
            $table->string('status',512)->nullable();
            $table->string('video_code',512)->nullable();
            $table->text('tags')->nullable();
            $table->tinyInteger('flag_hot')->default(0)->nullable();
            $table->tinyInteger('flag_featured')->default(0)->nullable();
            $table->tinyInteger('flag_most_read')->default(0)->nullable();
            $table->tinyInteger('flag_video')->default(0)->nullable();
            $table->string('flag',255)->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->integer('like')->default(0)->nullable();
            $table->integer('view')->default(0)->nullable();
            $table->integer('share')->default(0)->nullable();
            $table->integer('sort')->default(0)->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

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
        Schema::dropIfExists('news');
    }
}
