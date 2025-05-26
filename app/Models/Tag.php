<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    //
    protected $fillable = [
        'name',
    ];

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public function morphPosts() {
        return $this->morphedByMany(Post::class,'taggable');
    }

    public function morphVideos() {
        return $this->morphedByMany(Video::class,'taggable');
    }
}
