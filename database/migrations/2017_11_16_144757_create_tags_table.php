<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0)->unsigned()->index();
            $table->integer('category_id')->default(0)->unsigned()->index();
            $table->string('title');
            $table->string('slug')->nullable()->index();
            $table->text('teaser')->nullable();
            $table->text('content')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('hot')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('slug')->nullable()->index();
            $table->timestamps();
        });
        Schema::create('blog_tag', function (Blueprint $table) {
            $table->integer('blog_id')->nullable()->default(0)->unsigned()->index();
            $table->integer('tag_id')->nullable()->default(0)->unsigned()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('blog_tag');
    }
}
