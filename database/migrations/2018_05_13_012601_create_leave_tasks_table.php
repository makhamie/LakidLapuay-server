<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaveTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('leave_request_id');
            $table->foreign('leave_request_id')->references('id')->on('leave_requests');
            $table->unsignedInteger('task_id');
            $table->foreign('task_id')->references('id')->on('tasks');
            $table->date('approved_at');
            $table->date('rejected_at');
            $table->unsignedInteger('substitute_id');
            $table->foreign('substitute_id')->references('id')->on('users');
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
        Schema::dropIfExists('leave_tasks');
    }
}
