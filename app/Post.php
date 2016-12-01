<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //
    protected $table = 'post';
    public $timestamps = false;

    public function creator(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
