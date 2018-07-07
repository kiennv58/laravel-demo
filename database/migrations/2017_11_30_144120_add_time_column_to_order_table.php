<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTimeColumnToOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('design_time')->nullable()->after('kpi');
            $table->integer('produce_time')->nullable()->after('design_time');
            $table->integer('shipping_time')->nullable()->after('produce_time');
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('design_time');
            $table->dropColumn('produce_time');
            $table->dropColumn('shipping_time');
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
