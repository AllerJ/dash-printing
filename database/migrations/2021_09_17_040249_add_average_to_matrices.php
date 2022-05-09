<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAverageToMatrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matrices', function (Blueprint $table) {
            //
			$table->integer('average')->nullable();
			$table->integer('dash_base')->nullable();
			$table->integer('percent_upcharge')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matrices', function (Blueprint $table) {
            $table->dropColumn('average');
			$table->dropColumn('dash_base');
			$table->dropColumn('percent_upcharge');
        });
    }
}
