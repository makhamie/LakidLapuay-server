<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequesterIdToLeaveTasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_tasks', function (Blueprint $table) {
            //
            $table->unsignedInteger('requester_id')->default(1);
            $table->foreign('requester_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_tasks', function (Blueprint $table) {
            //
            $table->dropForeign('lists_requester_id_foreign');
            $table->dropColumn('requester_id');
        });
    }
}
