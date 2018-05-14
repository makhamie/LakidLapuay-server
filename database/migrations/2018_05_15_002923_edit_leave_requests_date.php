<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditLeaveRequestsDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn('rejected_at');
        });
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->date('rejected_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn('rejected_at');
        });
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->date('rejected_at');
        });
    }
}
