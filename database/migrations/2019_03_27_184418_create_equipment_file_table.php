<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment_file', function (Blueprint $table) {
            $table->unsignedBigInteger('equipment_id');
            $table->unsignedBigInteger('file_id');

            $table->foreign('equipment_id')->references('id')->on('equipments')
                ->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('files')
                ->onDelete('cascade');

            $table->primary(['equipment_id', 'file_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipment_file');
    }
}
