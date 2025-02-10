<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'isAdmin',
        'avatar' ,
    ];

    protected function getAvatarAttribute($value) {
        //die('here');  // This will help to check if the function is being triggered
        return $value ? asset('storage/avatars/'.$value) : 
        asset('/fallback-avatar.jpg');
    }
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getRouteKeyName()
    {
        return 'username'; // Laravel will now use "username" instead of "id" in routes
    }

    public function posts() {
        return $this->hasMany(Post::class , 'user_id');
    }

    public function followers() {
        return $this->hasMany(Follow::class , 'followed_user');
    }

    public function followingTheseUsers() {
        return $this->hasMany(Follow::class , 'user_id');
    }

    public function feedPosts() {
        return $this->hasManyThrough(Post::class, Follow::class , 'user_id','user_id', 'id' , 'followed_user');
    }

}
