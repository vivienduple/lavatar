<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Avatar extends Model
{

    protected $fillable = ['user_id', 'email', 'image'];

    public $timestamps = false;

    /**
     * @return User the user owner of the Avatar
     */
    public function user(){
        return $this->belongsTo('App\User');
    }
}
