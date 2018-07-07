<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnToProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->string('size')->nullable();
            $table->integer('quantity')->nullable()->default(0);
            $table->integer('page_id')->nullable()->default(0);
            $table->integer('page_cover_id')->nullable()->default(0);
            $table->integer('tape')->nullable()->default(0);
            $table->integer('can')->nullable()->default(0);
            $table->integer('spine')->nullable()->default(0);
            $table->integer('glue')->nullable()->default(0);
            $table->integer('color')->nullable()->default(0);//in mau
            $table->integer('cover_color')->nullable()->default(0);//mau bia
            $table->integer('plastic')->nullable()->default(0);//bong kinh
            $table->integer('odd_page')->nullable()->default(0);//1 mat
            $table->integer('hole')->nullable()->default(0); // duc lo
            $table->integer('size_file')->nullable()->default(0); // cong file
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
        Schema::dropIfExists('order_details');
    }
}
