<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    //
    protected $fillable = [
        'responsible_id',
        'substitute_id',
        'substituted_at',
        'finished_at'
    ];
}
