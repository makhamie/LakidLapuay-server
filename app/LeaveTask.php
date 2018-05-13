<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveTask extends Model
{
    //
    protected $fillable = [
        'leave_request_id',
        'task_id',
        'substitute_id',
        'approved_at',
        'rejected_at'
    ];
}
