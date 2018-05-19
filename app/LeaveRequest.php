<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    //
    protected $fillable = [
        'subordinate_id',
        'reason',
        'approved_at',
        'started_at',
        'finished_at',
        'rejected_at'
    ];

    public function requester() {
        return $this->hasOne('App\User', 'id', 'subordinate_id');
    }
}
