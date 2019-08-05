<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('assign_id')->nullable();
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('priority_id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('equipment_id')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('assign_id')->references('id')->on('users')
                ->onDelete('set null');
            $table->foreign('type_id')->references('id')->on('request_types')
                ->onDelete('cascade');
            $table->foreign('priority_id')->references('id')->on('request_priorities')
                ->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('request_statuses')
                ->onDelete('cascade');
            $table->foreign('equipment_id')->references('id')->on('equipments')
                ->onDelete('set null');

            $table->index('user_id');
            $table->index('assign_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requests');
    }
}
