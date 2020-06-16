<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ToDo extends Model 
{

    // /**
    //  * The attributes that are mass assignable.
    //  *
    //  * @var array
    //  */
    protected $fillable = [
        'user_id', 'label', 'details', 'due_time', 'is_checked'
    ];

    public function user()
    {
       return $this->belongsTo('App/User');
       
    }
}
