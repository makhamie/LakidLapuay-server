<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    //
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'supervisor_id',
        'subordinate_id'
    ];

    public function supervisor() {
        return $this->hasOne('App\User', 'id', 'supervisor_id');
    }

    public function subordinate() {
        return $this->hasOne('App\User', 'id', 'subordinate_id');
    }
}
