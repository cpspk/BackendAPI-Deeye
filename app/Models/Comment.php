<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    protected $appends = [
        'likes_count', 'liked', 'comments_count'
    ];

    protected $visible = [
        'id', 'profile_id', 
        'author', 'likes_count', 'liked',
        'comments_count'
    ];

    public function author()
    {
        return $this->belongsTo('App\Models\Profile', 'profile_id');
    }

    public function activity()
    {
        return $this->hasOne('App\Models\Activity', 'activity_id');
    }

    public function post()
    {
        return $this->belongsTo('App\Models\Post', 'post_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Comment', 'parent_id');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'parent_id');
    }

    public function getLikesCountAttribute()
    {
        return $this->activity->likes()->count();
    }

    public function getLikedAttribute()
    {
        $profile = Profile::where('user_id', Auth::user()->id)->first();
        return $this->activity->likes()->where('profile_id', $profile->id)->count() > 0;
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments->count();
    }
}