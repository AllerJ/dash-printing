<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Ignite\Support\Migration\MigratePermissions;

class CreateRestockLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restock_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('inventory_id');
            $table->integer('qty');
            $table->string('cost');
            $table->string('yield');
            $table->string('per_sheet');
            $table->text('supplier');
            $table->date('ordered_on');
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
        Schema::dropIfExists('restock_logs');
    }
}
