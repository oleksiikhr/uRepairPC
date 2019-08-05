<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('serial_number')->nullable();
            $table->string('inventory_number')->nullable();
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('manufacturer_id')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('type_id')->references('id')->on('equipment_types')
                ->onDelete('cascade');
            $table->foreign('manufacturer_id')->references('id')->on('equipment_manufacturers')
                ->onDelete('set null');
            $table->foreign('model_id')->references('id')->on('equipment_models')
                ->onDelete('set null');

            $table->index('user_id');
            $table->index('type_id'); // filter
            $table->index('manufacturer_id'); // filter
            $table->index('model_id'); // filter
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipments');
    }
}
