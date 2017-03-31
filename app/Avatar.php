<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{
    /**
     * @return the user owner of the Avatar
     */
    public function user(){
        return $this->belongsTo('App\User');
    }
}
