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
        'rejected_at',
        'requester_id'
    ];

    public function leave_request() {
        return $this->hasOne('App\LeaveRequest', 'id', 'leave_request_id');
    }
    public function task() {
        return $this->hasOne('App\Task', 'id', 'task_id');
    }
    public function requester() {
        return $this->hasOne('App\User', 'id', 'requester_id');
    }
}
