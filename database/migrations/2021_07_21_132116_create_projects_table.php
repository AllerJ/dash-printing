<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Ignite\Support\Migration\MigratePermissions;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('fourover_id')->nullable();
            $table->string('project_title');
            $table->string('client_type');
            $table->string('client_id')->nullable();
            $table->string('inventory_id');
            $table->string('finished_size');
            $table->string('per_sheet')->default('1');
            $table->string('sides')->default('1');
            $table->integer('qty');
            $table->string('base_cost')->nullable();
            $table->string('finishing_cost')->default('0');
            $table->string('shipping_cost')->default('0');
            $table->string('upcarge_percent')->nullable();
            $table->string('upcharge_amount')->default('0');
            $table->string('subtotal')->nullable();
            $table->string('tax1_amount')->default('0');
            $table->string('tax2_amount')->default('0');
            $table->string('total')->nullable();
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
        Schema::dropIfExists('projects');
    }
}
