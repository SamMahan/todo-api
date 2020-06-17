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
        'userId', 'label', 'details', 'due_time', 'is_checked','user_id'
    ];

    public function user()
    {
       return $this->belongsTo('App/User');
       
    }
}
