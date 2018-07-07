<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInitTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('product_code')->nullable()->index();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->integer('default_price')->nullable()->default(0);
            $table->integer('price_per_page')->nullable()->default(0);
            $table->double('kpi')->nullable()->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
        Schema::create('assets', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('type');
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->integer('default_price')->nullable()->default(0);
            $table->integer('import_price')->nullable()->default(0);
            $table->timestamps();
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_code')->nullable();
            $table->integer('staff_id');
            $table->integer('customer_id');
            $table->tinyInteger('status')->default(0);
            $table->double('kpi')->nullable()->default(0);
        });
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
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
        Schema::dropIfExists('products');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('assets');
    }
}
