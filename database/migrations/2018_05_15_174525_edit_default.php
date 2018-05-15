<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditDefault extends Migration
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
            $table->unsignedInteger('requester_id')->default(null)->change();
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
            $table->unsignedInteger('requester_id')->default(1)->change();
        });
    }
}
