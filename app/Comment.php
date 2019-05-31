<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    
    protected $fillable = [
        'request_id',
        'comment',
        'sender',
        'comment_at'
    ];
    
    public function request_items()
    {
        return $this->belongsTo('App\RequestItem', 'id', 'request_id');
    }
}
