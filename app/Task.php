<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    protected $fillable = [
        'subordinate_id',
        'name',
        'description',
        'supervisor_id',
        'started_at',
        'finished_at'
    ];
}
