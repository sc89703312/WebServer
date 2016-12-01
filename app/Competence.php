<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    //
    protected $table = 'competence';
    public $timestamps = false;

    /*
     * 每个活动都对应一个创建者
     */
    public function creator(){
        return $this->belongsTo('App\User','user_id', 'id');
    }
}
