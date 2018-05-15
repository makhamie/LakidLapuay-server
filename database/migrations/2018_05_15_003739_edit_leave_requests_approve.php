<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditLeaveRequestsApprove extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn('approved_at');
        });
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->date('approved_at')->nullable();
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
            $table->dropColumn('approved_at');
        });
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->date('approved_at');
        });
    }
}
