<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCauseIdToOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('staff_id', 'creator_id');
            $table->string('designer_id')->nullable()->after('staff_id');
            $table->string('producer_id')->nullable()->after('designer_id');
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
            $table->renameColumn('creator_id', 'staff_id');
            $table->dropColumn('designer_id');
            $table->dropColumn('producer_id');
        });
    }
}
