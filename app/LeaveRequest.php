<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    //
    protected $fillable = [
        'requester_id', 'substitute_id', 'start', 'end', 'task_id', 'reason', 'approved_by_substitute_at', 'approved_by_supervisor_at'
    ];
}
