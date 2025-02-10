<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    //
    protected $fillable = [
        'user_id',
        'followed_user'
    ];

    public function userDoFollow() {
        return $this->belongsTo(User::class , 'user_id');
    }

    public function userBeFollowed() {
        return $this->belongsTo(User::class , 'followed_user');
    }

}
