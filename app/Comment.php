<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $casts = [
        'created_at' => 'datetime:d M H:i',
    ];
    //
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
